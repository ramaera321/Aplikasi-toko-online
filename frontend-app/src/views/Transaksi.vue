<template>
    <div class="container">
        <div class="fw-bold mt-3 mb-2">
            <h5>
                Transaksi
            </h5>
        </div>
        <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-6 py-2 px-2" v-for="t in transaksi" :key="t.id">
                <div class="card w-100">
                    <img :src="imageBaseUrl + t.produk.path" class="card-img-top" onerror="javascript:this.src='http://localhost:8000/image/default-image.png'" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">{{ t.produk.nama_produk }}</h5>
                        <p class="card-text">{{ subString(t.produk.deskripsi) }}</p>
                        <button type="button" class="btn w-100 mb-2" :class="t.dibayar != NULL ? 'btn-success' : 'btn-warning'">{{ t.dibayar != NULL ? 'Lunas' : 'Belum Dibayar' }}</button>
                        <button class="btn btn-primary w-100" :disabled="t.dibayar != NULL" @click="showModal(t)">Bayar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal" :class="{'d-none':!isModalVisible}" id="modalForm" style="display: block">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="staticBackdropLabel">Pembayaran Transaksi</h5>
                        <button type="button" class="btn-close" aria-label="Close" @click="showModal(null)"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="tagihan" class="form-label">Total Tagihan</label>
                            <input v-model="tagihan" type="text" class="form-control" id="tagihan" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="kategori" class="form-label">Jumlah Uang</label>
                            <input v-model="dibayar" type="text" class="form-control" id="dibayar">
                            <input v-model="id_transaksi" type="hidden" class="form-control" id="id_transaksi">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" @click="showModal(null)">Close</button>
                        <button type="button" class="btn btn-primary" @click="submit">Bayar</button>
                    </div>
                </div>
            </div>
        </div>
</template>

<script>
import { onMounted, ref } from 'vue'
import axios from 'axios'
import Swal from 'sweetalert2'

export default {
    setup(){
        let id_transaksi = ref();
        let tagihan = ref();
        let dibayar = ref();
        let isModalVisible = ref(false);
        const imageBaseUrl = ref('http://localhost:8000/storage/');
        const transaksi = ref();
        const user = JSON.parse(localStorage.getItem('user'));

        const showModal = (data = null) =>{
            if(data != null){
                const transaksiData = JSON.parse(JSON.stringify(data));
                id_transaksi.value = transaksiData.id;
                tagihan.value = transaksiData.total_transaksi;
            }
            isModalVisible.value = !isModalVisible.value;
            dibayar.value = '';
        }

        const getProduk = async() => {
            await axios.get('transaksi/user/' + user.id)
                .then((response) => {
                    console.log(response)
                    transaksi.value = response.data.data;
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

        const submit = async() => {
            const formData = new FormData;
            formData.append("dibayar", dibayar.value);
            formData.append("kembalian", dibayar.value - tagihan.value ?? 0);

            if(dibayar.value - tagihan.value < 0){
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Gagal melakukan pembayaran, Uang tidak cukup',
                })
            } else {  
                await axios.post(`transaksi/bayar/${id_transaksi.value}`, formData)
                    .then((response) => {
                        if(response.status == 200){
                            Swal.fire({
                                title: 'Berhasil !!',
                                text: 'Berhasil melakukan pembayaran',
                                icon: 'success',
                            })
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: 'Gagal melakukan pembayaran',
                            })
                        }
                        getProduk();
                        isModalVisible.value = !isModalVisible.value;
                    })
                    .catch((error) => {
                        console.log(error)
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: 'Gagal melakukan pembayaran',
                        })
                    })
            }

        }

        onMounted(() => {
            getProduk();
        })

        return {
            isModalVisible,
            tagihan,
            id_transaksi,
            imageBaseUrl,
            transaksi,
            dibayar,
            subString,
            getProduk,
            showModal,
            submit,
        }
    }
}
</script>