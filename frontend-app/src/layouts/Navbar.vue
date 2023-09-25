<template>    
    <nav class="navbar navbar-expand-lg bg-light">
        <div class="container">
            <a class="navbar-brand" href="#">Toko</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" v-if="role == 'admin'">
                    <li class="nav-item mt-1">
                        <router-link class="nav-link active mx-2" to="/dashboard">Dashboard</router-link>
                    </li>
                    <li class="nav-item mt-1">
                        <router-link class="nav-link mx-2" to="/admin/produk">Produk</router-link>
                    </li>
                    <li class="nav-item mt-1">
                        <router-link class="nav-link mx-2" to="/admin/kategori">Kategori</router-link>
                    </li>
                    <li class="nav-item mt-1">
                        <router-link class="nav-link mx-2" to="/admin/transaksi">Transaksi</router-link>
                    </li>
                    <li class="nav-item mt-1">
                        <router-link class="nav-link mx-2" to="/admin/bukukas">Buku Kas</router-link>
                    </li>
                </ul>
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" v-if="role == 'user'">
                    <li class="nav-item mt-1">
                        <router-link class="nav-link active mx-2" to="/">Dashboard</router-link>
                    </li>
                    <li class="nav-item mt-1">
                        <router-link class="nav-link mx-2" to="/produk/list">Produk</router-link>
                    </li>
                    <li class="nav-item mt-1">
                        <router-link class="nav-link mx-2" to="/produk/transactions">Transaksi</router-link>
                    </li>
                </ul>
            <form class="d-flex" v-if="user != null">
                <div class="dropdown">
                    <a href="#" class="d-flex align-items-center text-dark text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <img :src="require('../../public/image/user.png')" alt="" width="32" height="32" class="rounded-circle me-2">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small" aria-labelledby="dropdownUser1" style="transform: translate3d(-100px, 10px, 0px)">
                        <li><a class="dropdown-item" @click="logout">Sign out</a></li>
                    </ul>
                </div>
            </form>
            </div>
        </div>
    </nav>   
</template>

<script>
import axios from 'axios';
import {useRouter} from 'vue-router';
import Swal from 'sweetalert2';
import { nextTick, onBeforeMount, ref } from 'vue';

export default {
    setup() {
        const router = useRouter();
        let isUser = ref(false);
        const user = JSON.parse(localStorage.getItem('user'));
        let role = ref();

        const getRole = async() => {
            role.value = user != null ? user.roles[0].name : null;
            isUser.value = user != null ? true : false;

            await nextTick();
        }

        const logout = async() => {
            await axios
                .post('/logout')
                .then((response) => {
                    console.log(response)
                    if(response.status == 200){
                        Swal.fire({
                            title: 'Berhasil !!',
                            text: 'Berhasil logout dari sistem',
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'Oke',
                        }).then(() => {
                            localStorage.removeItem("token");
                            localStorage.removeItem("user");
                            
                            router.go({path: '/login'});
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Logout tidak berhasil!',
                        })
                    }
                })
                .catch((error) => {
                    console.log(error)
                })
        }

        onBeforeMount(() => {
            getRole();
        })

        return {
            logout,
            user,
            role,
        }
    }
}
</script>