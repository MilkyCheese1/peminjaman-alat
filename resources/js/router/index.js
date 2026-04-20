import { createRouter, createWebHistory } from 'vue-router';
import LandingPage from '../pages/LandingPage.vue';
import Login from '../pages/Login.vue';
import Registrasi from '../pages/Registrasi.vue';
import Alat from '../pages/Alat.vue';
import PeminjamanSaya from '../pages/PeminjamanSaya.vue';
import AkunAdmin from '../pages/AkunAdmin.vue';
import AkunOwner from '../pages/AkunOwner.vue';
import AkunStaff from '../pages/AkunStaff.vue';
import AkunPeminjam from '../pages/AkunPeminjam.vue';
import StatistikAdmin from '../pages/StatistikAdmin.vue';
import StatistikOwner from '../pages/StatistikOwner.vue';
import StatistikStaff from '../pages/StatistikStaff.vue';
import StatistikPeminjam from '../pages/StatistikPeminjam.vue';
import ManagementUser from '../pages/ManagementUser.vue';
import ManagementAlat from '../pages/ManagementAlat.vue';
import ManagementKategori from '../pages/ManagementKategori.vue';
import ManagementPeminjaman from '../pages/ManagementPeminjaman.vue';
import LaporanStaff from '../pages/Laporan.vue';

const routes = [
  {
    path: '/',
    name: 'Landing',
    component: LandingPage,
  },
  {
    path: '/login',
    name: 'Login',
    component: Login,
  },
  {
    path: '/register',
    name: 'Registrasi',
    component: Registrasi,
  },
  {
    path: '/dashboard/admin',
    name: 'DashboardAdmin',
    redirect: '/statistik-admin',
    alias: '/dashboard/admin/',
  },
  {
    path: '/dashboard/owner',
    name: 'DashboardOwner',
    redirect: '/statistik-owner',
    alias: '/dashboard/owner/',
  },
  {
    path: '/dashboard/peminjam',
    name: 'DashboardPeminjam',
    redirect: '/statistik-peminjam',
    alias: '/dashboard/peminjam/',
  },
  {
    path: '/alat',
    name: 'Alat',
    component: Alat,
  },
  {
    path: '/peminjaman-saya',
    name: 'PeminjamanSaya',
    component: PeminjamanSaya,
  },
  {
    path: '/dashboard/staff',
    name: 'DashboardStaff',
    redirect: '/statistik-staff',
    alias: '/dashboard/staff/',
  },
  {
    path: '/akun-admin',
    name: 'AkunAdmin',
    component: AkunAdmin,
  },
  {
    path: '/akun-owner',
    name: 'AkunOwner',
    component: AkunOwner,
  },
  {
    path: '/akun-staff',
    name: 'AkunStaff',
    component: AkunStaff,
  },
  {
    path: '/akun-peminjam',
    name: 'AkunPeminjam',
    component: AkunPeminjam,
  },
  {
    path: '/statistik-admin',
    name: 'StatistikAdmin',
    component: StatistikAdmin,
  },
  {
    path: '/statistik-owner',
    name: 'StatistikOwner',
    component: StatistikOwner,
  },
  {
    path: '/statistik-staff',
    name: 'StatistikStaff',
    component: StatistikStaff,
  },
  {
    path: '/statistik-peminjam',
    name: 'StatistikPeminjam',
    component: StatistikPeminjam,
  },
  {
    path: '/management-user',
    name: 'ManagementUser',
    component: ManagementUser,
  },
  {
    path: '/management-alat',
    name: 'ManagementAlat',
    component: ManagementAlat,
  },
  {
    path: '/management-kategori',
    name: 'ManagementKategori',
    component: ManagementKategori,
  },
  {
    path: '/management-peminjaman',
    name: 'ManagementPeminjaman',
    component: ManagementPeminjaman,
  },
  {
    path: '/laporan-staff',
    name: 'LaporanStaff',
    component: LaporanStaff,
  },
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
