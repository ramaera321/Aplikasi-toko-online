<template>
    <div class="container">
        <div class="fw-bold mt-3 mb-2">
            <h5>
                Dashboard
            </h5>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-3 py-2 px-4">
                <div class="row bg-primary rounded text-white d-flex align-items-center card-info px-2">
                    <div class="col-8">
                        <h4>
                            Kategori
                        </h4>
                    </div>
                    <div class="col-4">
                        <h2 class="text-end">
                            {{ kategori }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 py-2 px-4">
                <div class="row bg-success rounded text-white d-flex align-items-center card-info px-2">
                    <div class="col-8">
                        <h4>
                            Produk
                        </h4>
                    </div>
                    <div class="col-4">
                        <h2 class="text-end">
                            {{ produk }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 py-2 px-4">
                <div class="row bg-warning rounded text-white d-flex align-items-center card-info px-2">
                    <div class="col-8">
                        <h4>
                            Transaksi
                        </h4>
                    </div>
                    <div class="col-4">
                        <h2 class="text-end">
                            {{ transaksi }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-3 py-2 px-4">
                <div class="row bg-warning rounded text-white d-flex align-items-center card-info px-2">
                    <div class="col-8">
                        <h4>
                            Jumlah Kas
                        </h4>
                    </div>
                    <div class="col-4">
                        <h2 class="text-end">
                            {{ bukukas ?? 0 }}
                        </h2>
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
        let produk = ref();
        let kategori = ref();
        let transaksi = ref();
        let bukukas = ref();

        const getAllData = async () => {
            await axios.get('/all')
                .then((response) => {
                    produk.value = response.data.data.produk;
                    kategori.value = response.data.data.kategori;
                    transaksi.value = response.data.data.transaksi;
                    bukukas.value = response.data.data.bukukas;
                })
                .catch((err) => {
                    console.log(err)
                })
        } 

        onMounted(() => {
            getAllData();
        })

        return {
            produk,
            kategori,
            transaksi,
            bukukas,
            getAllData,
        }
    }
}
</script>

<style scoped>
    .card-info{
        height: 80px;
    }
</style>