import { createRouter, createWebHistory } from 'vue-router';
import LandingPage from '../pages/LandingPage.vue';
import Login from '../pages/Login.vue';
import Registrasi from '../pages/Registrasi.vue';
import DashboardAdmin from '../pages/DashboardAdmin.vue';
import DashboardOwner from '../pages/DashboardOwner.vue';
import DashboardPeminjam from '../pages/DashboardPeminjam.vue';
import DashboardStaff from '../pages/DashboardStaff.vue';
import Alat from '../pages/Alat.vue';
import PeminjamanSaya from '../pages/PeminjamanSaya.vue';
import AkunAdmin from '../pages/AkunAdmin.vue';
import AkunOwner from '../pages/AkunOwner.vue';
import AkunStaff from '../pages/AkunStaff.vue';
import AkunPeminjam from '../pages/AkunPeminjam.vue';

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
    component: DashboardAdmin,
  },
  {
    path: '/dashboard/owner',
    name: 'DashboardOwner',
    component: DashboardOwner,
  },
  {
    path: '/dashboard/peminjam',
    name: 'DashboardPeminjam',
    component: DashboardPeminjam,
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
    component: DashboardStaff,
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
];

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes,
});

export default router;
