import { spawn } from 'node:child_process';
import { mkdirSync } from 'node:fs';
import { resolve } from 'node:path';

const root = process.cwd();
const dbDir = resolve(root, '.wp-data', 'database');
const uploadsDir = resolve(root, '.wp-data', 'uploads');

mkdirSync(dbDir, { recursive: true });
mkdirSync(uploadsDir, { recursive: true });

console.log('[AIRPORTCHA] 启动本地 WordPress（已启用 .wp-data/ 本地持久化）...');

const child = spawn(
  'npx',
  [
    '--yes',
    '@wp-playground/cli@3.1.65',
    'server',
    '--port=9400',
    '--blueprint=./blueprint.json',
    '--mount=./wp-content/themes/airportcha:/wordpress/wp-content/themes/airportcha',
    '--mount=./wp-content/plugins/airportcha-core:/wordpress/wp-content/plugins/airportcha-core',
    '--mount=./.wp-data/uploads:/wordpress/wp-content/uploads'
  ],
  { stdio: 'inherit', shell: true }
);

child.on('exit', code => process.exit(code ?? 0));
