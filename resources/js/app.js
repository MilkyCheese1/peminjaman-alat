import { createApp } from 'vue';
import App from './App.vue';
import router from './router';

function removeDummyLocalStorageData() {
  if (typeof window === 'undefined') {
    return;
  }

  const dummyCategoryIds = new Set(['kategori-1', 'kategori-2', 'kategori-3', 'kategori-4', 'kategori-5']);
  const dummyToolIds = new Set(['alat-1', 'alat-2', 'alat-3', 'alat-4', 'alat-5']);
  const dummyUserIds = new Set(['user-1', 'user-2', 'user-3', 'user-4', 'user-5']);
  const dummyBorrowingIds = new Set([
    'trx-001',
    'trx-002',
    'trx-003',
    'trx-004',
    'trx-005',
    'trx-006',
    'trx-007',
    'trx-008',
  ]);

  const entries = [
    { key: 'admin-management-categories', ids: dummyCategoryIds },
    { key: 'admin-management-tools', ids: dummyToolIds },
    { key: 'admin-management-users', ids: dummyUserIds },
    { key: 'staff-management-peminjaman', ids: dummyBorrowingIds },
  ];

  for (const entry of entries) {
    const raw = window.localStorage.getItem(entry.key);

    if (!raw) {
      continue;
    }

    try {
      const parsed = JSON.parse(raw);

      if (!Array.isArray(parsed)) {
        continue;
      }

      const filtered = parsed.filter((item) => !entry.ids.has(item?.id));

      if (filtered.length !== parsed.length) {
        window.localStorage.setItem(entry.key, JSON.stringify(filtered));
      }
    } catch (error) {
      // Ignore invalid JSON
    }
  }
}

removeDummyLocalStorageData();

createApp(App).use(router).mount('#app');
