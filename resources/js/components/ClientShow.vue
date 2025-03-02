<template>
    <div>
        <h1 class="mb-6">Clients -> {{ client.name }}</h1>

        <div class="flex">
            <div class="w-1/3 mr-5">
                <div class="w-full bg-white rounded p-4">
                    <h2>Client Info</h2>
                    <table>
                        <tbody>
                        <tr>
                            <th class="text-gray-600 pr-3">Name</th>
                            <td>{{ client.name }}</td>
                        </tr>
                        <tr>
                            <th class="text-gray-600 pr-3">Email</th>
                            <td>{{ client.email }}</td>
                        </tr>
                        <tr>
                            <th class="text-gray-600 pr-3">Phone</th>
                            <td>{{ client.phone }}</td>
                        </tr>
                        <tr>
                            <th class="text-gray-600 pr-3">Address</th>
                            <td>{{ client.address }}<br/>{{ client.postcode + ' ' + client.city }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="w-2/3">
                <div>
                    <button class="btn"
                            :class="{'btn-primary': currentTab == 'bookings', 'btn-default': currentTab != 'bookings'}"
                            @click="switchTab('bookings')">Bookings
                    </button>
                    <button class="btn"
                            :class="{'btn-primary': currentTab == 'journals', 'btn-default': currentTab != 'journals'}"
                            @click="switchTab('journals')">Journals
                    </button>
                </div>

                <!-- Bookings -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'bookings'">
                    <div class="flex justify-content-between">
                        <h3 class="mb-3">List of client bookings</h3>
                        <select v-model="filter" @change='filterBookings'>
                            <option selected value="all">All bookings</option>
                            <option value="future">Future bookings only</option>
                            <option value="past">Past bookings</option>
                        </select>
                    </div>
                    <template v-if="client.bookings && client.bookings.length > 0">
                        <table>
                            <thead>
                            <tr>
                                <th>Time</th>
                                <th>Notes</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="booking in client.bookings" :key="booking.id">
                                <td>{{ booking.start }} to {{ booking.end }}</td>
                                <td>{{ booking.notes }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" @click="deleteBooking(booking)">Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </template>

                    <template v-else>
                        <p class="text-center">The client has no bookings.</p>
                    </template>

                </div>

                <!-- Journals -->
                <div class="bg-white rounded p-4" v-if="currentTab == 'journals'">
                    <div class="flex justify-content-between">
                        <h3 class="mb-3">List of client bookings</h3>
                        <a :href="`${getClientId}/journals/create`" class="float-right btn btn-primary">+ New Journal</a></h1>
                    </div>
                    <template v-if="journals && journals.length > 0">
                        <table>
                            <thead>
                            <tr>
                                <th>Year</th>
                                <th>Text</th>
                                <th>Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="journal in journals" :key="journal.id">
                                <td>{{ journal.date }} </td>
                                <td>{{ journal.text }}</td>
                                <td>
                                    <button class="btn btn-danger btn-sm" @click="deleteJournal(journal)">Delete
                                    </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </template>

                    <template v-else>
                        <p class="text-center">The client has no journals.</p>
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    name: 'ClientShow',

    props: ['client'],

    computed: {
        getClientId() {
            return `${location.pathname.split('/')[2]}`
        }
    },

    data() {
        return {
            currentTab: 'bookings',
            filter: 'all',
            journals: []
        }
    },

    methods: {
        switchTab(newTab) {
            this.currentTab = newTab;

            if (this.currentTab === 'journals') {
                axios.get(`${this.getClientId}/journals`)
                    .then((response) => {
                        this.journals = response.data
                    }).catch(error => {
                    console.error(error)
                })
            }
        },

        async deleteBooking(booking) {
            try {
                const response = await axios.delete(`/clients/${this.getClientId}/bookings/${booking.id}`);

                if (response.status === 200) {
                    this.client.bookings = this.client.bookings.filter(clientBooking => clientBooking.id !== booking.id)

                    alert('Booking was successfully deleted!')
                }
            } catch (error) {
                console.log(error)
            }
        },

        async deleteJournal(journal) {
            try {
                const response = await axios.delete(`/clients/${this.getClientId}/journals/${journal.id}`);

                if (response.status === 200) {
                    this.journals = this.journals.filter(clientJournal => clientJournal.id !== journal.id)

                    alert('Journal was successfully deleted!')
                }
            } catch (error) {
                console.log(error)
            }
        },

        async filterBookings() {
            try {
                const response = await axios.get(`${this.getClientId}?filter=${this.filter}`)
                this.client = response.data
            } catch (error) {
                console.log(error)
            }
        }
    }
}
</script>
