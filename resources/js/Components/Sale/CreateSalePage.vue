<template>
    <div class="container-fluid">
        <div class="row">
            <!-- Billing Selection -->
            <div class="col-md-4 col-lg-4 p-2">
                <div class="card">
                    <div class="card-body">
                        <h4>Billed To</h4>
                        <div class="shadow-sm h-100 bg-white rounded-3 p-3 mt-4">
                            <div class="row">
                                <div class="col-8">
                                    <span class="text-bold text-dark">BILLED TO</span>
                                    <p class="text-xs mx-0 my-1">Name: <span>selectedCustomer-name</span></p>
                                    <p class="text-xs mx-0 my-1">Email: <span>selectedCustomer-email</span></p>
                                    <p class="text-xs mx-0 my-1">User ID: <span>selectedCustomer-id</span></p>
                                </div>
                                <div class="col-4">
                                    <p class="text-bold mx-0 my-1 text-dark">Invoice</p>
                                    <p class="text-xs mx-0 my-1">Date: slice date</p>
                                </div>
                            </div>
                            <hr class="mx-0 my-2 p-0 bg-secondary" />
                            <div class="row">
                                <div class="col-12">
                                    <table class="table w-100">
                                        <thead>
                                            <tr class="text-xs">
                                                <th>Name</th>
                                                <th>Qty</th>
                                                <th>Total</th>
                                                <th>Remove</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr class="text-center">
                                                <td> p name </td>
                                                <td> p unit</td>
                                                <td> p price</td>
                                                <td>
                                                    <button class="">-</button>
                                                    <button class="">+</button>
                                                    <button class="btn btn-danger btn-sm">Remove</button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <hr class="mx-0 my-2 p-0 bg-secondary" />
                            <div class="row">
                                <div class="col-12">
                                    <p class="text-bold text-xs my-1 text-dark">Total: <i
                                            class="bi bi-currency-dollar"></i> calculateTotal()</p>
                                    <p class="text-bold text-xs my-1 text-dark">VAT (vatRate%): <i
                                            class="bi bi-currency-dollar"></i> vatAmount</p>
                                    <p><button class="btn btn-info btn-sm my-1 bg-gradient-primary w-40">Apply
                                            VAT</button></p>
                                    <p><button class="btn btn-secondary btn-sm my-1 bg-gradient-primary w-40">Remove
                                            VAT</button></p>

                                    <p><span class="text-xxs">Discount Mode:</span></p>
                                    <select>
                                        <option value="false">Flat Discount</option>
                                        <option value="true">Percentage Discount</option>
                                    </select>
                                    <p class="text-bold text-xs my-1 text-dark">Discount: <i
                                            class="bi bi-currency-dollar"></i> discountAmount </p>
                                    <div>
                                        <span class="text-xxs">Flat Discount:</span>
                                        <input type="number" class="form-control w-40" min="0" />
                                        <p><button class="btn btn-warning btn-sm my-1 bg-gradient-primary w-40">Apply
                                                Flat Discount</button></p>
                                    </div>
                                    <div>
                                        <span class="text-xxs">Discount (%):</span>
                                        <input type="number" class="form-control w-40" min="0" max="100" step="0.25" />
                                        <p><button class="btn btn-warning btn-sm my-1 bg-gradient-primary w-40">Apply
                                                Percentage Discount</button></p>
                                    </div>
                                    <p><button class="btn btn-secondary btn-sm my-1 bg-gradient-primary w-40">Remove
                                            Discount</button></p>

                                    <hr class="mx-0 my-2 p-0 bg-secondary" />
                                    <p class="text-bold text-xs my-1 text-dark">Payable: <i
                                            class="bi bi-currency-dollar"></i> payable</p>
                                    <p><button
                                            class="btn btn-success btn-sm my-3 bg-gradient-primary w-40">Confirm</button>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Selection -->
            <!-- <div class="col-md-4 col-lg-4 p-2">
                <div class="card">
                    <div class="card-body">
                        <h4>Select Product</h4>
                        <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text" />
                        <EasyDataTable buttons-paginations alternating :items="ProductItem" :headers="ProductHeader"
                            :rows-per-page="10" :search-value="searchProductValue" :seach-field="searchProductField">
                            <template>
                                <img alt="Product Image" height="40px" width="40px" />
                            </template>

                            <template>
                                <button>
                                    'Stock Out'
                                </button>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div> -->

            <!-- Customer Selection -->
            <div class="col-md-4 col-lg-4 p-2">
                <div class="card">
                    <div class="card-body">
                        <h4>Select Customer</h4>
                        <input placeholder="Search..." class="form-control mb-2 w-auto form-control-sm" type="text" v-model="searchCustomerValue" />
                        <EasyDataTable buttons-pagination alternating :headers="CustomerHeader" :items="CustomerItem" show-index
                            :rows-per-page="10" :search-field="searchCustomerField" :search-value="searchCustomerValue">
                            <template #item-number="{ id, name }">
                                <button @click="addCustomerToSale({ id, name, email})" class="btn btn-success btn-sm">Add</button>
                            </template>
                        </EasyDataTable>
                    </div>
                </div>
            </div>
            {{ selectedCustomer }}
        </div>
    </div>
</template>

<script setup>
import { usePage, useForm, router } from '@inertiajs/vue3';
import { createToaster } from "@meforma/vue-toaster";
import { ref } from 'vue';

const toaster = createToaster({
    position: "top-right",
});

let page = usePage();

const selectedCustomer = ref(null)

const CustomerHeader = [
    { text: 'Name', value: 'name' },
    { text: 'Pick', value: 'number' },
]

const CustomerItem = ref(page.props.customers);

const searchCustomerValue = ref();
const searchCustomerField = ref(['name']);

const addCustomerToSale = (customer) => {
    selectedCustomer.value = customer;
}

</script>

<style scoped></style>
