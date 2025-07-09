<template>
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div>
                            <h3>Product</h3>
                        </div>
                        <hr />
                        <div class="float-end">
                            <a href="/ProductSavePage?id=0" class="btn btn-success mx-3 btn-sm">
                                Add Product
                            </a>
                        </div>
                        <div>
                            <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text"
                                v-model="searchValue">
                            <EasyDataTable buttons-pagination alternating :headers="Header" :items="Item" show-index
                                :rows-per-page="10" :search-field="searchField" :search-value="searchValue">

                                <template #item-img="{ image }" class="pt-2 pb-2">
                                    <img :src="image ? image : 'placeholder.png'" :alt="`image`" alt="" height="40px" width="40px">
                                </template>

                                <template #item-number="{ id, name }">
                                    <Link class="btn btn-success mx-3 btn-sm" :href="`/ProductSavePage?id=${id}`">Edit
                                    </Link>
                                    <button class="btn btn-danger btn-sm" @click="DeleteClick(id)">Delete</button>
                                </template>
                            </EasyDataTable>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
    import { Link, usePage, router } from '@inertiajs/vue3';
    import { ref } from 'vue';
    import { createToaster } from "@meforma/vue-toaster";

    const toaster = createToaster({
        position: "top-right",
    });

    const Header = [
        { text: 'Image', value: 'img' },
        { text: 'Name', value: 'name' },
        { text: 'Category', value: 'category.name' },
        { text: 'Price', value: 'price' },
        { text: 'Quantity', value: 'unit' },
        { text: 'Action', value: 'number' },
    ]

    const page = usePage();
    const Item = ref(page.props.products);

    const searchValue = ref()
    const searchField = ref(['category.name', 'name', 'price', 'unit'])

    const DeleteClick = (id) =>{
        let text = "Do you want to delete?";
        if(confirm(text) === true){
            router.get(`/delete-product/${id}`)
            toaster.success('Product deleted successfully');
        }else{
            text = "You canceled";
            toaster.error('You canceled');
        }
    }

</script>

<style scoped></style>
