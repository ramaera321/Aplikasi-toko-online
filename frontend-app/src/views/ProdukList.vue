<template>
    <div class="container">
        <div class="fw-bold mt-3 mb-2">
            <h5>
                Produk
            </h5>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 py-2 px-2" v-for="p in produk" :key="p.id">
                <div class="card w-100">
                    <img :src="imageBaseUrl + p.path" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ p.nama_produk }}</h5>
                        <p class="card-text">{{ subString(p.deskripsi) }}</p>
                        <router-link class="btn btn-primary w-100" :to="{name:'DetailProduk', params:{id: p.id}}">Detail</router-link>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, ref } from 'vue'
import axios from 'axios'

export default {
    setup(){
        const produk = ref();
        const imageBaseUrl = ref('http://localhost:8000/storage/');

        const getProduk = async() => {
            await axios.get('produk/paginate')
                .then((response) => {
                    produk.value = response.data.data.data;
                })
                .catch((error) => {
                    console.log(error);
                });
        }

        const subString = (deskrip) => {
            let deskripsiProduk = deskrip.substr(0, 30);
            if(deskrip.length > 30)
                return deskripsiProduk + "...";
            else
                return deskripsiProduk;
        }

        onMounted(() => {
            getProduk();
        })

        return {
            produk,
            imageBaseUrl,
            subString,
            getProduk,
        }
    }
}
</script>