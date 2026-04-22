<template>
  <div class="min-h-screen flex flex-col bg-slate-50 dark:bg-slate-950 lg:flex-row">
    <SidebarPeminjam />
    <div class="flex-1 flex flex-col">
      <Navbar />
      <main class="flex-1 p-4 sm:p-6">
        <div class="mb-8">
          <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Profil Peminjam</h1>
          <p class="text-slate-700 dark:text-slate-300">Kelola informasi akun Anda</p>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
          <div class="lg:col-span-1">
            <AccountAvatarCard fallback-name="Peminjam User" fallback-role="Peminjam">
              <template #menu>
                <div class="mt-6 border-t border-slate-200 pt-5 text-left dark:border-white/10">
                  <p class="text-xs font-semibold uppercase tracking-[0.22em] text-slate-500 dark:text-slate-400">Menu Akun</p>
                  <div class="mt-3 grid grid-cols-1 gap-2">
                    <a href="#data-diri" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800">Data Diri</a>
                    <a href="#keamanan-akun" class="rounded-2xl border border-slate-200 px-4 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-100 dark:border-white/10 dark:text-slate-200 dark:hover:bg-slate-800">Keamanan Akun</a>
                  </div>
                </div>
              </template>
            </AccountAvatarCard>
          </div>

          <div class="lg:col-span-2">
            <AccountSettingsCard />
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import SidebarPeminjam from '../components/layout/SidebarPeminjam.vue'
import Navbar from '../components/layout/Navbar.vue'
import AccountAvatarCard from '../components/account/AccountAvatarCard.vue'
import AccountSettingsCard from '../components/account/AccountSettingsCard.vue'

export default {
  name: 'AkunPeminjam',
  components: {
    SidebarPeminjam,
    Navbar,
    AccountAvatarCard,
    AccountSettingsCard,
  },
}
</script>
