<template>
    <div class="container mt-5">
        <h2 class="mb-4">Current Subscription</h2>
        <div class="card mb-4">
            <div class="card-body">
                <div v-if="currentSubscription">
                    <div class="row mb-2">
                        <strong class="col-sm-6">Status:</strong>
                        <span class="col-sm-6">{{ currentSubscription.status }}</span>
                    </div>
                    <div class="row mb-2">
                        <strong class="col-sm-6">Tariff:</strong>
                        <span class="col-sm-6">{{ currentSubscription.tariff_name }}</span>
                    </div>
                    <div class="row mb-2">
                        <strong class="col-sm-6">Number of Users:</strong>
                        <span class="col-sm-6">{{ currentSubscription.user_count }}</span>
                    </div>
                    <div class="row mb-2">
                        <strong class="col-sm-6">Total Cost:</strong>
                        <span class="col-sm-6">{{ currentSubscription.total_cost }}€</span>
                    </div>
                    <div class="row mb-2">
                        <strong class="col-sm-6">Payment Frequency:</strong>
                        <span class="col-sm-6">{{ currentSubscription.payment_frequency }}</span>
                    </div>
                    <div class="row mb-2">
                        <strong class="col-sm-6">Valid Until:</strong>
                        <span class="col-sm-6">{{ formatDate(currentSubscription.valid_until) }}</span>
                    </div>
                </div>
                <div v-else>
                    <p>No current subscription found.</p>
                </div>
            </div>
        </div>

        <h2 class="mb-4">Next Subscription</h2>
        <div class="card mb-4">
            <div class="card-body">
                <form @submit.prevent="saveNextSubscription">
                    <p v-if="nextSubscription.id" class="row mb-2">
                        <strong class="col-sm-6">Status:</strong>
                        <span class="col-sm-6">{{ nextSubscription.status }}</span>
                    </p>

                    <div class="mb-3 row">
                        <label for="tariff" class="col-sm-6 col-form-label fw-bold">Tariff:</label>
                        <div class="col-sm-6">
                            <select v-model="nextSubscription.tariff_id" class="form-select" required>
                                <option value="" disabled>Select default tariff</option>
                                <option v-for="tariff in tariffs" :key="tariff.id" :value="tariff.id">
                                    {{ tariff.name }} - {{ tariff.price_per_user }}€
                                </option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3 row">
                        <label for="user_count" class="col-sm-6 col-form-label fw-bold">Number of Users:</label>
                        <div class="col-sm-6">
                            <input type="number" v-model="nextSubscription.user_count" class="form-control" required/>
                        </div>
                    </div>

                    <p v-if="nextSubscription.id" class="row mb-2">
                        <strong class="col-sm-6">Total Cost:</strong>
                        <span class="col-sm-6">{{ nextSubscription.total_cost }}€</span>
                    </p>

                    <div class="mb-3 row">
                        <label for="payment_frequency" class="col-sm-6 col-form-label fw-bold">Payment Frequency:</label>
                        <div class="col-sm-6">
                            <select v-model="nextSubscription.payment_frequency" class="form-select" required>
                                <option value="monthly">Monthly</option>
                                <option value="yearly">Yearly</option>
                            </select>
                        </div>
                    </div>

                    <p v-if="nextSubscription.id" class="row mb-2">
                        <strong class="col-sm-6">Valid Until:</strong>
                        <span class="col-sm-6">{{ formatDate(nextSubscription.valid_until) }}</span>
                    </p>

                    <button type="submit" class="btn btn-primary">
                        {{ nextSubscription.id ? 'Update Subscription' : 'Save Subscription' }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            currentSubscription: null,
            nextSubscription: {
                tariff_id: '',
                user_count: 1,
                payment_frequency: 'monthly',
            },
            tariffs: [],
        };
    },
    created() {
        this.fetchSubscriptions();
        this.fetchTariffs();
    },
    methods: {
        async fetchSubscriptions() {
            const response = await axios.get('/api/subscriptions');
            this.currentSubscription = response.data.currentSubscription;
            this.nextSubscription = response.data.nextSubscription || {
                tariff_id: '',
                user_count: 1,
                payment_frequency: 'monthly'
            };
        },
        async fetchTariffs() {
            const response = await axios.get('/api/tariffs');
            this.tariffs = response.data;
        },
        async saveNextSubscription() {
            if (this.nextSubscription.id) {
                await axios.put(`/api/subscriptions/${this.nextSubscription.id}`, this.nextSubscription);
            } else {
                await axios.post('/api/subscriptions', this.nextSubscription);
            }
            await this.fetchSubscriptions();
        },
        formatDate(dateString) {
            const options = {year: 'numeric', month: '2-digit', day: '2-digit'};
            return new Date(dateString).toLocaleDateString(undefined, options);
        },
    },
};
</script>

<style scoped>
</style>
