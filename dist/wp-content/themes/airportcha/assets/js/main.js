(function () {
  // 1. Mobile Navigation Toggle
  var menuBtn = document.getElementById('ac-menu-toggle');
  var nav = document.getElementById('ac-primary-nav');
  if (menuBtn && nav) {
    menuBtn.addEventListener('click', function () {
      var open = nav.classList.toggle('is-open');
      menuBtn.setAttribute('aria-expanded', open ? 'true' : 'false');
    });
  }

  // 2. Search Drawer Toggle + Static Search Enhancement
  var searchBtn = document.getElementById('ac-search-toggle');
  var searchDrawer = document.getElementById('ac-search-drawer');
  if (searchBtn && searchDrawer) {
    searchBtn.addEventListener('click', function () {
      var open = searchDrawer.classList.toggle('is-open');
      if (open) {
        var input = searchDrawer.querySelector('input[type="search"]');
        if (input) input.focus();
      }
    });
  }

  // 3. One-Click Coupon Copy
  document.addEventListener('click', function (e) {
    var btn = e.target.closest('.ac-copy-btn');
    if (!btn) return;
    var text = btn.getAttribute('data-copy');
    if (!text) return;
    var done = function () {
      var orig = btn.textContent;
      btn.textContent = '已复制';
      btn.classList.add('is-copied');
      setTimeout(function () {
        btn.textContent = orig;
        btn.classList.remove('is-copied');
      }, 1800);
    };
    if (navigator.clipboard && navigator.clipboard.writeText) {
      navigator.clipboard.writeText(text).then(done).catch(done);
    } else {
      var ta = document.createElement('textarea');
      ta.value = text;
      document.body.appendChild(ta);
      ta.select();
      try { document.execCommand('copy'); } catch (_) {}
      document.body.removeChild(ta);
      done();
    }
  });

  // 4. Static-Compatible Brand Directory Filter & Sort
  var brandGrid = document.getElementById('ac-brand-directory-grid');
  var sortSelect = document.getElementById('ac-brand-sort');
  if (brandGrid && sortSelect) {
    sortSelect.addEventListener('change', function () {
      var mode = sortSelect.value;
      var cards = Array.prototype.slice.call(brandGrid.querySelectorAll('.ac-brand-card'));
      cards.sort(function (a, b) {
        if (mode === 'rating') {
          return parseFloat(b.getAttribute('data-rating') || '0') - parseFloat(a.getAttribute('data-rating') || '0');
        }
        return parseInt(a.getAttribute('data-order') || '99', 10) - parseInt(b.getAttribute('data-order') || '99', 10);
      });
      cards.forEach(function (c) { brandGrid.appendChild(c); });
    });
  }

  // 5. Static Full-Text Search (works offline without WordPress PHP)
  var searchForm = searchDrawer ? searchDrawer.querySelector('.ac-search-form') : null;
  if (searchForm) {
    var resultsBox = document.createElement('div');
    resultsBox.className = 'ac-static-search-results';
    searchDrawer.querySelector('.ac-container').appendChild(resultsBox);

    var searchIndex = null;
    var inputEl = searchForm.querySelector('input[type="search"]');

    function renderResults(q) {
      if (!q || !searchIndex) {
        resultsBox.innerHTML = '';
        return;
      }
      var lower = q.toLowerCase();
      var hits = searchIndex.filter(function (item) {
        return (item.title + ' ' + item.excerpt + ' ' + item.type).toLowerCase().indexOf(lower) !== -1;
      }).slice(0, 8);

      if (!hits.length) {
        resultsBox.innerHTML = '<p class="ac-search-empty">未找到与「' + q.replace(/</g, '&lt;') + '」相关的内容。</p>';
        return;
      }
      resultsBox.innerHTML = hits.map(function (h) {
        return '<a class="ac-search-hit" href="' + h.url + '"><strong>' + h.title + '</strong><span>' + h.type + '</span></a>';
      }).join('');
    }

    if (inputEl) {
      inputEl.addEventListener('input', function () {
        var q = inputEl.value.trim();
        if (!q) { resultsBox.innerHTML = ''; return; }
        if (searchIndex) {
          renderResults(q);
        } else {
          fetch('/_data/search-index.json')
            .then(function (r) { return r.ok ? r.json() : null; })
            .then(function (data) {
              if (Array.isArray(data)) {
                searchIndex = data;
                renderResults(q);
              }
            })
            .catch(function () {});
        }
      });
    }
  }
})();

// Phase 3: Static-Compatible Ranking Board Filter & Multi-Sort
(function () {
  var boards = document.querySelectorAll('[data-ac-rank-board]');
  boards.forEach(function (board) {
    var tbody = board.querySelector('tbody');
    var sortEl = board.querySelector('[data-rank-sort]');
    var filterBtns = board.querySelectorAll('[data-rank-filter]');
    if (!tbody) return;

    var activeFeature = 'all';

    function updateRows() {
      var rows = Array.prototype.slice.call(tbody.querySelectorAll('[data-rank-row]'));
      var mode = sortEl ? sortEl.value : 'order';

      rows.sort(function (a, b) {
        if (mode === 'rating') {
          return parseFloat(b.getAttribute('data-rating') || '0') - parseFloat(a.getAttribute('data-rating') || '0');
        }
        if (mode === 'price') {
          return parseFloat(a.getAttribute('data-price') || '9999') - parseFloat(b.getAttribute('data-price') || '9999');
        }
        return parseInt(a.getAttribute('data-order') || '99', 10) - parseInt(b.getAttribute('data-order') || '99', 10);
      });

      var rank = 1;
      rows.forEach(function (row) {
        var feats = (row.getAttribute('data-features') || '').split(',');
        var show = activeFeature === 'all' || feats.indexOf(activeFeature) !== -1;
        row.style.display = show ? '' : 'none';
        if (show) {
          var numEl = row.querySelector('.ac-rank-num');
          if (numEl) numEl.textContent = String(rank++);
        }
        tbody.appendChild(row);
      });
    }

    filterBtns.forEach(function (btn) {
      btn.addEventListener('click', function () {
        filterBtns.forEach(function (b) { b.classList.remove('is-active'); });
        btn.classList.add('is-active');
        activeFeature = btn.getAttribute('data-rank-filter') || 'all';
        updateRows();
      });
    });

    if (sortEl) {
      sortEl.addEventListener('change', updateRows);
    }
  });
})();

// Phase 4: Auto Table of Contents (TOC) for Review Pages
(function () {
  var body = document.getElementById('ac-review-body');
  var tocNav = document.getElementById('ac-auto-toc');
  var tocList = document.getElementById('ac-toc-list');
  if (!body || !tocNav || !tocList) return;

  var headings = body.querySelectorAll('h2');
  if (headings.length < 2) return;

  headings.forEach(function (h, idx) {
    if (!h.id) h.id = 'sec-' + (idx + 1);
    var li = document.createElement('li');
    var a = document.createElement('a');
    a.href = '#' + h.id;
    a.textContent = h.textContent;
    li.appendChild(a);
    tocList.appendChild(li);
  });
  tocNav.style.display = 'block';
})();
