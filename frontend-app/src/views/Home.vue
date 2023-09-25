<template>
    <div class="container">
        <div class="fw-bold mt-3 mb-2">
            <h5>
                Dashboard
            </h5>
        </div>
        <div class="row">
            <div class="col-sm-12 col-md-6 col-lg-4 py-2 px-4">
                <div class="row bg-primary rounded text-white d-flex align-items-center card-info px-2">
                    <div class="col-10">
                        <h4>
                            Barang Dibeli
                        </h4>
                    </div>
                    <div class="col-2">
                        <h2>
                            {{ transaksi }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 py-2 px-4">
                <div class="row bg-success rounded text-white d-flex align-items-center card-info px-2">
                    <div class="col-10">
                        <h4>
                            Sudah Dibayar
                        </h4>
                    </div>
                    <div class="col-2">
                        <h2>
                            {{ sudah_dibayar }}
                        </h2>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-6 col-lg-4 py-2 px-4">
                <div class="row bg-warning rounded text-white d-flex align-items-center card-info px-2">
                    <div class="col-10">
                        <h4>
                            Belum Dibayar
                        </h4>
                    </div>
                    <div class="col-2">
                        <h2>
                            {{ belum_dibayar }}
                        </h2>
                    </div>
                </div>
            </div>
        </div>        
    </div>
</template>

<script>
import axios from 'axios'
import { onMounted, ref } from 'vue'

export default {
    setup(){
        let transaksi = ref();
        let sudah_dibayar = ref();
        let belum_dibayar = ref();
        const user = JSON.parse(localStorage.getItem('user'));

        const getCountData = async() => {
            await axios.get(`/transaksi/user/${user.id}/count`)
                .then((response) => {
                    // console.log(response)
                    transaksi.value = response.data.data.transaksi;
                    belum_dibayar.value = response.data.data.belum_dibayar;
                    sudah_dibayar.value = response.data.data.sudah_dibayar;
                })
                .catch((error) => {
                    console.log(error)
                })
        }

        onMounted(() => {
            getCountData();
        })

        return {
            transaksi,
            belum_dibayar,
            sudah_dibayar,
        }
    }
}
</script>

<style scoped>
    .card-info{
        height: 80px;
    }
</style>