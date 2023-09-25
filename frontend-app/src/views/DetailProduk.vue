<template>
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-sm-12 pt-4">
                <img src="https://picsum.photos/500/400" alt="">
            </div>
            <div class="col-lg-6 col-sm-12">
                <div class="fw-bold mt-3 mb-2">
                    <h6>
                        Detail Produk
                    </h6>
                </div>
                <div class="mb-2">
                    <h5>{{ produk.nama_produk }}</h5>
                </div>
                <div class="fw-bold mb-2 px-3">
                    <!-- {{ rupiah(produk.harga) }} -->
                    {{ produk.harga }}
                </div>
                <div>
                    {{ produk.deskripsi }}
                </div>
                <div class="mt-4 mb-4">
                    <form class="row row-cols-lg-auto g-3 align-items-center">
                        <label class="form-label fw-bold">Masukkan Jumlah Barang</label>
                        <div class="d-flex w-100 my-0">
                            <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" id="subtract" @click="subtraction">-</button>
                            <div class="mx-2 col-3">
                                <input type="text" v-model="qty" class="form-control form-control-sm text-center" id="inlineFormInputGroupUsername" readonly>
                            </div>
                            <button type="button" class="btn btn-primary btn-sm px-4 fw-bold" id="plus" @click="add">+</button>
                        </div>
                    </form>
                </div>
                <div class="mt-4 pt-2">
                    <div class="mb-2">
                        <input type="text" v-model="result" class="form-control w-50" id="exampleInputEmail1" aria-describedby="emailHelp" readonly>
                    </div>
                    <button class="w-50 btn btn-primary" @click="transaction">Beli Produk</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, ref } from 'vue'
import axios from 'axios';
import { useRoute, useRouter } from 'vue-router';
import Swal from 'sweetalert2';

export default {
    setup(){
        let qty = ref(1);
        let price = ref();
        let result = ref();
        let produk = ref(1);
        const router = useRouter();
        const route = useRoute();
        const id = ref(route.params.id);
        const user = JSON.parse(localStorage.getItem('user'));

        const add = () => {
            qty.value += 1;
            result.value = price.value * qty.value;
        }

        const subtraction = () => {
            if(qty.value > 1){
                qty.value -= 1;
            }
            result.value = price.value * qty.value;
        }

        // const rupiah = (nominal) => {
        //     const harga = nominal.replace(/(?<=[0-9])(?=(?:[0-9]{3})+(?![0-9]))/g, ".")

        //     return 'Rp.' + harga;
        // }
        
        const getProduk = async() => {
            await axios.get(`/produk/${id.value}/show`)
                .then((response) => {
                    // console.log(response);
                    produk.value = response.data.data;
                    price.value = response.data.data.harga;
                    result.value = price.value;
                })
                .catch((error) => {
                    console.log(error)
                })
        }

        const transaction = async() => {
            const dateNow = new Date;
            const date = dateNow.getDate() + '-' + dateNow.getMonth() + '-' + dateNow.getFullYear();
            const formData = new FormData;
            formData.append("pembeli_id", user.id);
            formData.append("produk_id", id.value);
            formData.append("jumlah_produk", qty.value);
            formData.append("total_transaksi", result.value);
            formData.append("tanggal_transaksi", date.toString());
             Swal.fire({
                title: 'Peringatan !!',
                text: `Apakah anda yakin ingin melakukan transaksi ini ?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Oke',
            }).then((result) => {
                if(result.isConfirmed){
                    submit(formData)
                }
            })
        }

        const submit = async(formData) => {
            await axios.post('/transaksi/store', formData)
                .then((response) => {
                    console.log(response)
                    Swal.fire({
                        title: 'Berhasil !!',
                        text: `Berhasil melakukan transaksi ?`,
                        icon: 'success',
                        showCancelButton: true,
                        confirmButtonText: 'Oke',
                    }).then((result) => {
                        if(result.isConfirmed){
                            router.push({name: 'Transaksi'})
                        }
                    })
                })
                .catch((error) => {
                    console.log(error)
                })
        }

        onMounted(() => {
            getProduk();
        })

        return {
            qty,
            produk,
            result,
            // rupiah,
            add,
            subtraction,
            transaction,
        }
    }
}
</script>