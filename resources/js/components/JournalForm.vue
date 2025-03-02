<template>
    <div>
        <h1 class="mb-6">Clients -> Add New Journal</h1>
        <div class="max-w-lg mx-auto">
            <div class="form-group">
                <label for="text">Text</label>
                <input type="text" id="text" class="form-control" v-model="journal.text">
                <span v-if="errors.text" class="text-red-600">{{ errors.text[0] }}</span>
            </div>
            <div class="form-group">
                <label for="email">Year</label>
                <input type="number" min="1900" max="2099" step="1" class='form-control' v-model="journal.date">
                <span v-if="errors.date" class="text-red-600">{{ errors.date[0] }}</span>
            </div>

            <div class="text-right">
                <a href="/clients" class="btn btn-default">Cancel</a>
                <button @click="storeJournal" class="btn btn-primary">Create</button>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientForm',

    props: ['journals'],

    computed: {
        getClientId() {
            return `${location.pathname.split('/')[2]}`
        }
    },

    data() {
        return {
            client: {
                name: '',
                email: '',
                phone: '',
                address: '',
                city: '',
                postcode: '',
            },
            journal: {
                text: '',
                year: ''
            },
            errors: {},
        }
    },

    methods: {
        storeJournal() {
            axios.post(`/clients/${this.getClientId}/journals`, this.journal)
                .then((data) => {
                    window.location.href = data.data.url;
                }).catch((error) => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors
                } else {
                    console.error('Something bad happened', error)
                }
            })
        }
    }
}
</script>
