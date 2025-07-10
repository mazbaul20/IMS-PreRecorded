<template>
    <div class="container-fluid">
        <div class="row d-flex justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <div class="float-end">
                            <a href="/ProductPage" class="btn btn-success mx-3 btn-sm">
                                Back
                            </a>
                        </div>
                        <form @submit.prevent="submit" enctype="multipart/form-data">
                            <div class="card-body">
                                <h4>Save Product</h4>
                                <input id="id" hidden name="id" v-model="form.id" placeholder="Product ID" class="form-control"
                                    type="text" />
                                <br />
                                <input id="name" name="name" v-model="form.name" placeholder="Product Name"
                                    class="form-control" type="text" />
                                <br />
                                <input id="price" name="price" v-model="form.price" placeholder="Product Price"
                                    class="form-control" type="text" />
                                <br />
                                <input id="unit" name="unit" v-model="form.unit" placeholder="Product Unit"
                                    class="form-control" type="text" />
                                <br />
                                <!-- Category Dropdown -->
                                <div>
                                    <label for="category">Select Category:</label>
                                    <select v-model="form.category_id" class="form-control" id="category">
                                        <option value="" disabled>Select a category</option>

                                        <option v-for="category in categories" :key="category.id" :value="category.id">{{ category.name}}</option>

                                    </select>
                                </div>
                                <br />
                                <div>
                                    <label for="image">Product Image:</label> <br>
                                    <ImageUpload :productImage="form.image" @image="(e) => form.image = e"/>
                                </div>
                                <br />
                                <button type="submit" class="btn w-100 btn-success">Save Change</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { useForm, usePage, router, Link } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";

const toaster = createToaster({
    position: "top-right",
});
import { ref } from 'vue';
import ImageUpload from './ImageUpload.vue';

const urlParams = new URLSearchParams(window.location.search);
let id = ref(parseInt(urlParams.get('id')));
console.log(id.value);

const form = useForm({
    id: id.value,
    name: '',
    price: '',
    unit: '',
    category_id: '',
    image: null,
});

const page = usePage();
let categories = page.props.categories;
let product = page.props.product;

let URL = "/create-product";

if(id.value !==0 && product !== null){
    URL = "/update-product";
    form.name = product.name;
    form.price = product.price;
    form.unit = product.unit;
    form.category_id = product.category_id;
    form.image = product.image;
}

function submit(){
    if(form.name.length === 0){
        toaster.warning("Name is required");
    }else if(form.price.length === 0){
        toaster.warning("Price is required");
    }else if(form.unit.length === 0){
        toaster.warning("Unit is required");
    }else if(form.category_id.length === 0){
        toaster.warning("Category is required");
    }else{
        form.post(URL,{
            onSuccess: () => {
                if(page.props.flash.status === true){
                    toaster.success(page.props.flash.message);
                    router.get('/ProductPage');
                }else{
                    toaster.error(page.props.flash.message);
                }
            }
        });
    }
}

</script>

<style scoped></style>
