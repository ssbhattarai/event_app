<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header display-6">
                        Event App
                        <button class="btn btn-secondary float-end me-5" type="submit" data-bs-toggle="modal"
                                data-bs-target="#myModal"
                                @click.prevent="addEvent">Add
                        </button>
                    </div>

                    <div class="card-body table-responsive">
                        <div class="row">
                            <div class="col-4">
                                <select class="form-select" aria-label="Default select example" v-model="filter_events">
                                    <option value="finished_events">Finished Events</option>
                                    <option value="upcoming_events">Upcoming Events</option>
                                    <option value="upcoming_events_within_7_days">Upcoming Events within 7 days</option>
                                    <option value="finished_events_of_7_days">Finished Events of 7 Days</option>
                                </select>
                            </div>
                            <div class="col-4">
                                <input type="text" class="form-control" id="eventTitle" placeholder="Search Any Event"
                                       v-model="q">
                            </div>
                            <div class="col-4">
                                <button class="btn btn-dark ms-1" type="button"
                                        @click="clearFilters">Clear Filter
                                </button>
                            </div>
                        </div>
                        <table class="table table-striped">
                            <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Title</th>
                                <th scope="col">Start Date</th>
                                <th scope="col">End Date</th>
                                <!--                                <th scope="col">Is Live ?</th>-->
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="(event, i) in events.data" :key="i">
                                <th scope="row">{{ page === 1 ? (i + 1) : ((page - 1) * perPage) + (i + 1) }}</th>
                                <td>{{ event.title }}</td>
                                <td>{{ event.formatted_start_date }}</td>
                                <td>{{ event.formatted_end_date }}</td>
                                <!--                                <td>-->
                                <!--                                    <span class="badge bg-secondary">{{-->
                                <!--                                            event.is_live === 1 ? 'Active' : 'inactive'-->
                                <!--                                        }}</span>-->
                                <!--                                </td>-->
                                <td>
                                    <button class="btn btn-outline-secondary" type="button" @click="editEvent(event)">
                                        Edit
                                    </button>
                                    <!--                                    <button class="btn btn-outline-dark ms-2" type="button">View</button>-->
                                    <button class="btn btn-outline-danger ms-1" type="button"
                                            @click="deleteEvent(event.id)">Delete
                                    </button>
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

        <!--        Add Edit Modal-->
        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
             aria-labelledby="staticBackdropLabel" aria-hidden="true">
            <div class="modal-dialog modal-xl">
                <form @submit.prevent="isCreate ? saveData() : updateData()">
                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">{{ title }}</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!--                            <template v-if="errors.length > 0">-->
                            <div class="alert alert-danger pt-1 pb-1 alert-dismissible" role="alert"
                                 v-for="(error, i) in errors" :key="i">
                                {{ error[0] }}
                            </div>
                            <!--                            </template>-->
                            <div class="row">
                                <div class="col-12">
                                    <label for="eventTitle" class="form-label">Event Title *</label>
                                    <input type="text" class="form-control" id="eventTitle" placeholder="Event Title *"
                                           v-model="formData.title" required>
                                </div>
                                <div class="col-4 mt-2">
                                    <label for="startDate" class="form-label">Start Date *</label>
                                    <input type="datetime-local" class="form-control" id="startDate"
                                           v-model="formData.start_date" required>
                                </div>
                                <div class="col-4 mt-2">
                                    <label for="endDate" class="form-label">End Date *</label>
                                    <input type="datetime-local" class="form-control" id="endDate"
                                           v-model="formData.end_date" required>
                                </div>
                                <div class="col-12 mt-2">
                                    <label for="description">Description *</label>
                                    <textarea class="form-control"
                                              id="description" rows="3" required
                                              v-model="formData.description"></textarea>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">{{ this.formData.id ? 'Update' : 'Save' }}
                            </button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>
</template>

<script>
import axiosInstance from "../api";
import {Bootstrap5Pagination} from 'laravel-vue-pagination';
import {Modal} from 'bootstrap'


export default {
    data() {
        return {
            isCreate: false,
            events: [],
            page: null,
            perPage: null,
            title: null,
            formData: {
                id: null
            },
            errors: [],
            url: 'events?',
            modal: null,
            filter_events: null,
            q: null
        }
    },
    components: {
        Bootstrap5Pagination
    },
    mounted() {
        this.getEvents(1, this.url);
    },
    methods: {
        clearFilters() {
            this.filter_events = null
            this.q = null

            this.getEvents(1, this.url);
        },
        saveData() {
            this.errors = [];
            axiosInstance.post('events', this.formData).then(res => {
                this.modal.hide();
                this.errors = [];
                this.formData = {};
                this.getEvents(1);
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            });
        },
        updateData() {
            axiosInstance.patch(`events/${this.formData.id}`, this.formData).then(res => {
                this.modal.hide();
                this.getEvents(1);
                this.formData = {}
                this.errors = []
            }).catch(error => {
                if (error.response.status === 422) {
                    this.errors = error.response.data.errors;
                }
            });
        },

        getEvents(page, url) {
            this.page = page;
            axiosInstance.get(`${url}page=${page}`).then(res => {
                this.events = res.data;
                this.perPage = res.data.meta.per_page
            })
        },
        addEvent() {
            this.isCreate = true;
            this.title = "Add New Event";
            this.formData = {}

            this.modal = new Modal(document.getElementById("staticBackdrop"));
            this.modal.show();
        },
        editEvent(event) {
            this.isCreate = false;
            this.title = "Edit Event";

            this.formData = event;

            this.modal = new Modal(document.getElementById("staticBackdrop"));
            this.modal.show();
        },
        deleteEvent(id) {
            if (confirm("Are you sure ?")) {
                axiosInstance.delete(`events/${id}`).then(res => {
                    this.getEvents(1);
                });
            }
        }
    },
    watch: {
        filter_events(newVal) {

            this.getEvents(1, `${this.url}filter_events=${newVal}&search_key=${this.q}&`);
        },
        q(newVal) {
            this.getEvents(1, `${this.url}filter_events=${this.filter_events}&search_key=${newVal}&`);
        }
    }
}
</script>
