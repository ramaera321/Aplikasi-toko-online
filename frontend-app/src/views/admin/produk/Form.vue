<template>
    <div class="container">
        <div class="fw-bold mt-3 mb-2">
                <h5>
                    Create Produk
                </h5>
            </div> 
        <div class="row">
            <div class="col-sm-12 col-lg-8 py-2">
                <div class="card">
                    <div class="card-body">
                        <form>
                            <div class="mb-3">
                                <label for="nama_produk" class="form-label">Nama</label>
                                <input v-model="data.nama_produk" type="text" class="form-control" id="nama_produk">
                            </div>
                            <div class="mb-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select v-model="data.kategori" class="form-select" aria-label="Default select example">
                                    <option selected disabled hidden>Pilih Kategori Produk</option>
                                    <option v-for="k in kategori" :key="k.id" :value="k.id">{{ k.nama_kategori }}</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label for="stok" class="form-label">Stok</label>
                                <input v-model="data.stok" type="text" class="form-control" id="stok">
                            </div>
                            <div class="mb-3">
                                <label for="harga" class="form-label">Harga</label>
                                <input v-model="data.harga" type="text" class="form-control" id="harga">
                            </div>
                            <div class="mb-3">
                                <label for="deskripsi" class="form-label">Deskripsi</label>
                                <textarea v-model="data.deskripsi" class="form-control" id="deskripsi" rows="3"></textarea>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-lg-4 py-2">
                <div class="card">
                    <div class="card-body">
                        <label for="stokInput" class="form-label">Sampul Buku</label>
                        <div class="rounded w-100 border imagePreviewWrapper"
                        :style="{'background-image' : `url(${ imageUrl == null ? require('../../../../public/image/default-image.png') : imageUrl })`}">
                        </div>
                        <div class="mb-1">
                            <input class="form-control" ref="fileInput" type="file" id="formFile" @input="pickFile" @change="handleFileChange">
                        </div>
                    </div>
                </div>
                <div class="mt-2">
                    <button type="submit" class="btn btn-primary w-100" @click="submit">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import { onMounted, reactive, ref } from 'vue'
import axios from 'axios'
import { useRouter, useRoute } from 'vue-router'
import Swal from 'sweetalert2'

export default {
    setup(props, {emit}){
        const router = useRouter();
        const route = useRoute();
        const fileInput = ref([]);
        let kategori = ref();
        let imageUrl = ref();
        let path = ref();
        const id = ref(route.params.id ?? null);

        const data = reactive({
            nama_produk: '',
            kategori: '',
            stok: 0,
            harga: 0,
            deskripsi: '',
        });

        // const selectImage = () => {
        //     fileInput.click();
        // }
        const pickFile = () => {
            let input = fileInput.value;
            let file = input.files;
            if (file && file[0]) {
            let reader = new FileReader
            reader.onload = e => {
                imageUrl.value = e.target.result
            }
            reader.readAsDataURL(file[0])
                emit('input', file[0])
            }
        }
        const handleFileChange = (e) => {
            path.value = e.target.files[0];
        }

        const getKategori = async() => {
            await axios.get('/kategori')
                .then((response) => {
                    kategori.value = response.data.data;
                })
                .catch((err) => {
                    console.log(err);
                })
        }

        const getProduk = async() => {
            await axios.get(`/produk/${id.value}/show`)
                .then((response) => {
                    const produk_result = response.data.data;
                    console.log(produk_result);
                    data.nama_produk = produk_result.nama_produk;
                    data.kategori = produk_result.kategori_id;
                    data.stok = produk_result.stok;
                    data.harga = produk_result.harga;
                    data.deskripsi = produk_result.deskripsi;
                    imageUrl.value = 'http://localhost:8000/storage/' + produk_result.path;
                })
                .catch((err) => {
                    console.log(err);
                })
        }

        const submit = async() => {
            const formData = new FormData;
            formData.append("nama_produk", data.nama_produk);
            formData.append("kategori_id", data.kategori);
            formData.append("stok", data.stok);
            formData.append("harga", data.harga);
            formData.append("deskripsi", data.deskripsi);
            formData.append("path", path.value);

            let url = ref();
            let message_success = ref();
            let message_error = ref();

            if(id.value != null){
                url.value = `/produk/${id.value}/update`;
                message_success.value = 'Berhasil mengubah produk';
                message_error.value = 'Gagal mengubah produk';
            } else {
                url.value = '/produk/store';
                message_success.value = 'Berhasil create produk baru';
                message_error.value = 'Gagal create produk';
            }

            await axios.post(url.value, formData)
                .then((response) => {
                    if(response.status == 200){
                        Swal.fire({
                            title: 'Berhasil !!',
                            text: message_success.value,
                            icon: 'success',
                            showCancelButton: false,
                            confirmButtonText: 'Oke',
                        }).then(() => {
                            router.push({name: 'AdminProduk'})
                        })
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Oops...',
                            text: message_error.value,
                        })
                    }
                })
                .catch((error) => {
                    console.log(error)
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: message_error.value,
                    })
                })
        }

        onMounted(() => {
            if(id.value != null){
                getProduk();
            }
            getKategori();
        })

        return {
            data, 
            kategori,
            imageUrl,
            fileInput,
            submit,
            // selectImage,
            handleFileChange,
            pickFile,
            // getProduk,
        }
    }
}
</script>

<style scoped>
    .imagePreviewWrapper {
        height: 340px; 
        margin-bottom: 15px;
        display: block;
        cursor: pointer;
        background-size: cover;
        background-position: center center;
    }
</style>