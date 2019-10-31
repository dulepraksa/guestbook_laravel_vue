<template>
    <div class="home-visits">
        <div class="home-visits-header">
            <div class="title">
                <h3>Visits</h3>
            </div>
            <div v-if="loggedIn">
                <a class="cursor-pointer open-modal-a"  @click="openVisitModal">Create a visit</a>
            </div>
            <div v-else>
            </div>
            <div class="filters"> 
                <a :class="[ filter === 'active' ? 'active' : '' ]" @click="activeVisits">Active</a>
                <a :class="[ filter === 'today' ? 'active' : '' ]" @click="todayVisits">Today</a>
                <a :class="[ filter === 'yesterday' ? 'active' : '' ]" @click="yesterdayVisits">Yesterday</a>
                <a :class="[ filter === 'thisWeek' ? 'active' : '' ]" @click="thisWeekVisits">This Week</a>
                <a :class="[ filter === 'planned' ? 'active' : '' ]" @click="plannedVisits">Planned Visits</a>
            </div>
        </div>
        <div class="table-div no-shadow">
            <table class="guest-list-table">
                <thead>
                    <tr>
                        <th>Guest</th>
                        <th v-if="filter != 'planned'">Time of arrival</th>
                        <th v-if="filter != 'planned'">Date of arrival of arrival</th>
                        <th v-if="filter == 'planned'">Planned time</th>
                        <th v-if="filter == 'planned'">Planned date</th>
                        <th v-if="filter != 'planned'">Left at</th>
                        <th>Description</th>
                        <th v-if="loggedIn">Actions</th>
                    </tr>
                </thead>         
                <tbody>
                    <tr v-for="visit in visits" :key="visit.id">
                        <td>{{visit.user.name}} {{visit.user.surname}}</td>
                        <td v-if="filter != 'planned'">{{timeReformat(visit.arrived_at)}}</td>
                        <td v-if="filter != 'planned'">{{dateReformat(visit.arrived_at)}}</td>
                        <td v-if="filter == 'planned'">{{timeReformat(visit.planned_time)}}</td>
                        <td v-if="filter == 'planned'">{{dateReformat(visit.planned_time)}}</td>
                        <td v-if="filter != 'planned'">{{timeReformat(visit.left_at)}}</td>
                        <td>{{description(visit.description)}}</td>
                        <td><button v-if="filter=='active' && loggedIn" class="red-modal-button" @click="closeVisit(visit.id)"><i class="fa fa-close"></i>  Close visit</button>
                        <button @click="openEditModal(visit)" v-if="loggedIn" class="red-modal-button"> <i class="fa fa-search"></i> Edit visit </button>  </td>
                    </tr>
                </tbody>
                    <tfoot>
                        <div>
                            <pagination :meta="meta" v-on:pagination:switched='promeni'></pagination>
                        </div>
                    </tfoot>
            </table>
        </div>
        <div>
            <create-visits-modal  @close-visit-modal="closeVisitModal" :modal="modal"></create-visits-modal>
        </div>
        <div>
            <div class="create-meeting-modal" v-if="editModal == true">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-header">
                            <h3>Update Visit</h3>
                        </div>
                        <div class="modal-body">
                            <div class="modal-image">
                                <img :src="returnImage()" alt="">
                            </div>
                            <div class="modal-checkbox">
                                <label for="">Check if its planned</label>
                                <input type="checkbox" v-model="visitForEditing.is_planned" name="" id="">
                            </div>
                            <div v-if="visitForEditing.is_planned == false"  class="modal-item">
                                <label for="">Arrived at time</label>
                                <input type="datetime-local" v-model="visitForEditing.arrived_at">
                                <span v-if="errors.arrived_at">{{errors.arrived_at[0]}}</span>
                            </div>
                            <div v-if="visitForEditing.is_planned == true"  class="modal-item">
                                <label for="">Planned time</label>
                                <input type="datetime-local" v-model="visitForEditing.planned_time" name="" id="">
                                <span v-if="errors.planned_time">{{errors.planned_time[0]}}</span>
                            </div>
                            <div class="modal-item">
                                <label for="">Left at</label>
                                <input type="datetime-local" v-model="visitForEditing.left_at" name="" id="">
                                <span v-if="errors.left">{{errors.left_at[0]}}</span>
                            </div>
                            <div class="modal-item">
                                <label for="">Description</label>
                                <input type="text" v-model="visitForEditing.description">
                                <span v-if="errors.description">{{errors.description[0]}}</span>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="red-modal-button" @click="updateVisit(visitForEditing.id)">Submit</button>
                            <button class="red-modal-button" @click="closeEditingModal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import CreateVisitsModal from '../addtitonal_components/CreateVisitsModal'
import pagination from '../addtitonal_components/Pagination'
import moment from 'moment'

function zeroPadding(num, digit) {
    var zero = '';
    for(var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}

export default {
    components: {
        CreateVisitsModal,
        pagination
    },
    data(){
        return {
            errors: [],
            modal: false,
            filter : 'active',
            editModal: false,
            query: '',
            errors: [],
            visitForEditing: {
                id: '',
                userId: '',
                planned_time: '',
                is_planned: '',
                arrived_at: '',
                left_at: '',
                description: '',
                image: '',
                image_folder: ''
            },
        }
    },
    created() {
        this.$store.dispatch('filteredVisits')
    },
    mounted() {
        this.assignFilter()
        this.description()
        this.$store.dispatch('filteredVisits')
    },
    computed: {
        meta() {
            return this.$store.state.visitsMeta
        },
        visits() {
            return this.$store.getters.getActiveVisits
        },
        loggedIn() {
            return this.$store.getters.loggedIn
        },
        storeFilter() {
            return this.$store.state.filter
        }
    },
    methods: {
        closeVisitModal() {
            this.modal =! this.modal
        },
        openVisitModal() {
            if(!this.loggedIn) {
                confirm('You are not logged in, redirect to login?')
                    this.$router.push({name: 'login'})
            } else {
                this.modal = true
            }
        },
        assignFilter() {
            this.$store.dispatch('assignFilter', this.filter)
        },
        filteredVisits() {
            this.$store.dispatch('filteredVisits')
        },
        closeVisit(visitId) {
            if(!this.loggedIn) {
                confirm('You are not logged in, redirect to login page?')
                    this.$router.push({name: 'login'})
            }
            if (this.loggedIn) {
                let leftAt = new Date();
                this.$store.dispatch('closeVisit',{
                    id: visitId,
                    left_at: leftAt
                })
                .then(() => {
                    this.$store.dispatch('filteredVisits')
                })
            }  else {
                this.$router.push({name : 'login'})
            }
        },
        plannedVisits(page = this.$route.query.params) {
            this.filter = 'planned'
            this.$store.dispatch('assignFilter', this.filter)
            this.$store.dispatch('filteredVisits', page)
        },
        activeVisits(page = this.$route.query.params) {
            this.filter = 'active'
            this.$store.dispatch('assignFilter', this.filter)
            this.$store.dispatch('filteredVisits', page)
        },
        todayVisits(page = this.$route.query.params){
            this.filter = 'today'
            this.$store.dispatch('assignFilter', this.filter)
            this.$store.dispatch('filteredVisits', page)
        },
        yesterdayVisits(page = this.$route.query.params){
            this.filter = 'yesterday'
            this.$store.dispatch('assignFilter', this.filter)
            this.$store.dispatch('filteredVisits', page)
        },
        thisWeekVisits(page = this.$route.query.params){
            this.filter = 'thisWeek'
            this.$store.dispatch('assignFilter', this.filter)        
            this.$store.dispatch('filteredVisits', page)
        },
        promeni(page = 1) {
            if (this.storeFilter == 'active'){
                this.$store.dispatch('filteredVisits', page)
            }
            if(this.storeFilter == 'today') {
                this.$store.dispatch('filteredVisits', page)
            }
            if(this.storeFilter == 'yesterday') {
                this.$store.dispatch('filteredVisits', page)
            }
            if(this.storeFilter == 'thisWeek') {
                this.$store.dispatch('filteredVisits', page)
            }
            if(this.storeFilter == 'planned') {
                this.$store.dispatch('filteredVisits', page)
            }
        },
        timeReformat(timeString) {
            if (timeString != null) {
                let time = new Date(timeString);
                let newTime = zeroPadding(time.getHours(),2) + ':' + zeroPadding(time.getMinutes(),2)
                return newTime
            } else {
                return "Active"
            }
        },
        dateReformat(timeString) {
            let date = new Date(timeString);
            // let newDate = date.getDay() + '/' + date.getMonth() + '/' + date.getFullYear() 
            let newDate = zeroPadding(date.getDate(), 2 ) + '.' + zeroPadding(date.getMonth()+1, 2) + '.' + zeroPadding(date.getFullYear(), 4)
            return newDate
        },
        openEditModal(visit) {
            this.visitForEditing.id = visit.id
            this.visitForEditing.userId = visit.user.id
            this.visitForEditing.arrived_at = this.formatDate(visit.arrived_at)
            this.visitForEditing.is_planned = this.giveBoolean(visit.planned)
            this.visitForEditing.planned_time = this.formatDate(visit.planned_time)
            this.visitForEditing.left_at = this.formatDate(visit.left_at)
            this.visitForEditing.description = visit.description
            this.visitForEditing.image = visit.user.image
            this.visitForEditing.image_folder = visit.user.sub_folder
            this.editModal = true
        },
        formatDate(date) {
            return moment(date).format('YYYY-MM-DDTHH:mm')
        },
        giveBoolean(isPlannned) {
            if(isPlannned == 1) {
                return true
            } else {
                return false
            }
        },
        closeEditingModal() {
            this.editModal = false
        },
        returnImage() {
            console.log(this.visitForEditing.image)
            if(this.visitForEditing.image !== null) {
                return "/images/" + this.visitForEditing.image_folder + '/' + this.visitForEditing.image
            }
            if(this.visitForEditing.image === null) {
                return "https://www.w3schools.com/howto/img_avatar.png"
            }
        },
        updateVisit(visitId) {
            this.$store.dispatch('updateVisit' ,{
                id: visitId,
                user_id: this.visitForEditing.userId,
                arrived_at: this.visitForEditing.arrived_at,
                planned: this.visitForEditing.is_planned,
                planned_time: this.visitForEditing.planned_time,
                left_at: this.visitForEditing.left_at,
                description: this.visitForEditing.description
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
        description(desc) {
            if(desc === null || desc === 'undefined') {
                return '- No description -'
            } else {
                return desc
            }
        }
    }
}
</script>

<style>
    .no-shadow {
        box-shadow: none !important;
    }
</style>
