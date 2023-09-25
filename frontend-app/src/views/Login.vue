<template>
    <div class="container">
        <div class="row">
            <div class="col-sm-6 pe-4">
                <img src="../../public/image/login.jpg" alt="" class="w-100">
            </div>
            <div class="col-sm-6 d-flex justify-content-center align-items-center ps-4">
                <div class="col-7">
                    <div>
                        <h3>Selamat Datang</h3>
                        <p>
                            Silakan login untuk masuk ke apalikasi
                        </p>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="text-center mb-2">
                                <h5>Login Form</h5>
                            </div>
                            <form @submit.prevent="submit">
                                <div class="mb-3">
                                    <label for="username" class="form-label">Username</label>
                                    <input v-model="username" type="text" class="form-control" id="username" aria-describedby="emailHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password</label>
                                    <input v-model="password" type="password" class="form-control" id="password">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <p>
                            Belum punya akun? silakan <router-link to="/register">registrasi</router-link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {useRoute, useRouter} from 'vue-router';
import axios from 'axios';
import {ref} from 'vue';
import Swal from 'sweetalert2';

export default {
    name: 'Login',
    setup(){
        const username = ref();
        const password = ref();
        const router = useRouter();
        const route = useRoute();

        const submit = async() => {
            await axios.post('/login', {
                username: username.value,
                password: password.value,
            })
            .then((response) => {
                if(response.status == 200){
                    Swal.fire({
                        title: 'Berhasil !!',
                        text: 'Berhasil login ke sistem',
                        icon: 'success',
                        showCancelButton: false,
                        confirmButtonText: 'Oke',
                    }).then(() => {
                        console.log(response)
                        localStorage.setItem('user', JSON.stringify(response.data.user));
                        localStorage.setItem('token', response.data.access_token);
                        axios.defaults.headers.common = {'Authorization' : response.data.access_token};

                        if(route.query.length > 0){
                            router.push({path: route.query.returnUrl})
                        } else {
                            if(response.data.user.roles[0].name === 'admin'){
                                router.push({path: '/dashboard'})
                            } else {
                                router.push({path: '/'})
                            }
                        }

                    })
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Login tidak berhasil!',
                    })
                }
            })
            .catch((err) => {
                console.log(err)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Login tidak berhasil!',
                })
            })
        }

        return {
            username,
            password,
            submit,
        }
    }
}
</script>