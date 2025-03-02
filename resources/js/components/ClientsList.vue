<template>
    <div>
        <h1>
            Clients
            <a href="/clients/create" class="float-right btn btn-primary">+ New Client</a>
        </h1>

        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Number of Bookings</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="client in userClients" :key="client.id">
                    <td>{{ client.name }}</td>
                    <td>{{ client.email }}</td>
                    <td>{{ client.phone }}</td>
                    <td>{{ client.bookings_count }}</td>
                    <td>
                        <a class="btn btn-primary btn-sm" :href="`/clients/${client.id}`">View</a>
                        <button class="btn btn-danger btn-sm" @click="deleteClient(client)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientsList',

    mounted() {
        this.userClients = JSON.parse(JSON.stringify(this.clients));
    },

    props: ['clients'],

    data() {
        return {
            userClients: {}
        }
    },

    methods: {
        async deleteClient(client) {
            try {
                const response = await axios.delete(`/clients/${client.id}`);

                if (response.status === 200) {
                    this.userClients = this.userClients.filter(userClient => userClient.id !== client.id)

                    alert('Client was successfully deleted!')
                }
            } catch (error) {
                console.log(error)
            }
        }
    }
}
</script>
