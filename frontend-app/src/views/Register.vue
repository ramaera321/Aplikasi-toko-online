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
                            Daftarkan akun anda untuk bisa login ke aplikasi
                        </p>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <form @submit.prevent="submit">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Nama</label>
                                    <input v-model="name" type="input" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Username</label>
                                    <input v-model="username" type="input" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp">
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Password</label>
                                    <input v-model="password" type="password" class="form-control" id="exampleInputPassword1">
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                    <div>
                        <p>
                            Sudah punya akun? silakan <router-link to="/login">login</router-link>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
// import {useRouter} from 'vue-router';
import axios from 'axios';
import {ref} from 'vue';

export default {
    name: 'Register',
    setup(){
        const name = ref();
        const username = ref();
        const password = ref();

        const submit = async() => {
            await axios.post('/register', {
                name: name.value,
                username: username.value,
                password: password.value,
            })
            .then((response) => {
                console.log(response)
            })
            .catch((err) => {
                console.log(err)
            })
        }

        return {
            name,
            username,
            password,
            submit,
        }
    }
}
</script>