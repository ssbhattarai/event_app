<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header display-6">Event App</div>

                    <div class="card-body table-responsive">
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <th scope="col">Is Live ?</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(event, i) in events.data" :key="i">
                                <th scope="row" >{{page === 1 ? (i + 1) : ((page - 1) * perPage) + (i + 1)}}</th>
                                <td>{{ event.title }}</td>
                                <td>{{ event.start_date }}</td>
                                <td>{{ event.end_date }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ event.is_live === 1 ? 'Active' :  'inactive' }}</span>
                                </td>
                                <td>
                                    <button class="btn btn-outline-secondary" type="submit">Edit </button>
                                    <button class="btn btn-outline-dark ms-2" type="submit">View </button>
                                    <button class="btn btn-outline-danger ms-1" type="submit">Delete </button>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-end">
                            <Bootstrap5Pagination :data="events"
                                                  align="right"
                                                  size="small"
                                                  @pagination-change-page="getEvents"/>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axiosInstance from "../api";
import { Bootstrap5Pagination } from 'laravel-vue-pagination';


export default {
    data() {
        return {
            events: [],
            page: null,
            perPage:null
        }
    },
    components:{
        Bootstrap5Pagination
    },
    mounted() {
        this.getEvents(1);
    },
    methods: {
        getEvents(page) {
            this.page = page;
            axiosInstance.get(`events?page=${page}`).then(res =>{
                this.events = res.data;
                this.perPage = res.data.meta.per_page
            })
        }
    },
    watch: {}
}
</script>
