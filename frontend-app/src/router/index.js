import { createRouter, createWebHashHistory } from 'vue-router'
import {Role} from '../middleware/role.js'
import Swal from 'sweetalert2';

const routes = [
  {
    path: '/dashboard',
    name: 'Dashboard',
    component: () => import('../views/Dashboard.vue'),
    meta: { autorize: [Role.Admin] },
  },
  {
    path: '/admin/bukukas',
    name: 'AdminBukuKas',
    component: () => import('../views/admin/bukukas/BukuKas.vue'),
    meta: { autorize: [Role.Admin] },
  },
  {
    path: '/admin/transaksi',
    name: 'AdminTransaksi',
    component: () => import('../views/admin/transaksi/Transaksi.vue'),
    meta: { autorize: [Role.Admin] },
  },
  {
    path: '/admin/kategori',
    name: 'AdminKategori',
    component: () => import('../views/admin/kategori/Kategori.vue'),
    meta: { autorize: [Role.Admin] },
  },
  {
    path: '/admin/produk',
    name: 'AdminProduk',
    component: () => import('../views/admin/produk/Produk.vue'),
    meta: { autorize: [Role.Admin] },
  },
  {
    path: '/admin/produk/create',
    name: 'AdminProdukCreate',
    component: () => import('../views/admin/produk/Form.vue'),
    meta: { autorize: [Role.Admin] },
  },
  {
    path: '/admin/produk/edit/:id',
    name: 'AdminProdukEdit',
    component: () => import('../views/admin/produk/Form.vue'),
    props: true,
    meta: { autorize: [Role.Admin] },
  },
  {
    path: '/',
    name: 'Home',
    component: () => import('../views/Home.vue'),
    meta: { autorize: [Role.User] },
  },
  {
    path: '/detail/produk/:id',
    name: 'DetailProduk',
    component: () => import('../views/DetailProduk.vue'),
    props: true,
    meta: { autorize: [Role.User] },
  },
  {
    path: '/produk/list',
    name: 'ProdukList',
    component: () => import('../views/ProdukList.vue'),
    meta: { autorize: [Role.User] },
  },
  {
    path: '/produk/transactions',
    name: 'Transaksi',
    component: () => import('../views/Transaksi.vue'),
    meta: { autorize: [Role.User] },
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('../views/Login.vue'),
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('../views/Register.vue'),
  },
];

const router = createRouter({
    history: createWebHashHistory(),
    routes,
    linkActiveClass: "active",
  })

  router.beforeEach((to, from, next) => {
    const { autorize } = to.meta;
    const user = JSON.parse(localStorage.getItem('user'));
    const role = user != null ? user.roles[0].name : null;

    if(autorize){
        if(!user){
          Swal.fire({
            title: 'Oops..!!',
            text: `Silakan login terlebih dahulu!!`,
            icon: 'warning',
            showCancelButton: false,
            confirmButtonText: 'Oke',
          })
          return next({ path: '/login', query: {returnUrl: to.path} });
        }

        if(autorize.length && !autorize.includes(role)){
            if(role === 'admin'){
                return next({ path: '/dashboard' })
              } else if(role === 'user'){
                return next({ path: '/' })
            }
            return next({ path: '/register' })
        }
    }

    next();
})

  export default router