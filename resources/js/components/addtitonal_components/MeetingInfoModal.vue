<template>
    <div>
        <div class="create-meeting-modal">
            <div class="modal-wrapper">
                <div class="meeting-info-container">
                    <div class="modal-header">
                        <h3>{{meeting.name}}</h3>
                    </div>
                    <div class="modal-body">
                        <div class="modal-body-header">
                            <div class="modal-item-wrapper">
                                <div class="">
                                    <label for="">Description</label>
                                </div>
                                <div class="input-button">
                                    <input v-model="toSubmit.description" style="padding: 3px;" type="text">
                                </div>
                                <div class="input-button">
                                    <span class="errors" v-if="errors.description">{{errors.description[0]}}</span>
                                </div>
                            </div>
                            <div class="modal-item-wrapper">
                                <div class="">
                                    <label for="">Start time:</label>
                                </div>
                                <div class="input-button">
                                    <input v-model="toSubmit.start_time" style="padding: 1px;" type="datetime-local">
                                </div>
                                <div>
                                    <span class="errors" v-if="errors.start_time">{{errors.start_time[0]}}</span>
                                </div>
                            </div>
                            <div class="modal-item-wrapper">
                                <div class="">
                                    <label for="">Room</label>
                                </div>
                                <div class="input-button">
                                    <select @change="changeRoom($event)"  style="padding: 3px;" type="text">
                                        <option v-for="room in rooms" :key="room.id" :selected="room.id == meeting.room_id" :value="toSubmit.room_id">{{room.name}}</option>
                                    </select>
                                    <div>
                                        <span class="errors" v-if="errors.room">{{errors.room[0]}}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-item-wrapper">
                                <div class="">
                                    <label for="">Add participants</label>
                                </div>
                                <div class="input-button-search">
                                    <input type="text" v-model="query">
                                    <div class="search-results" v-if="results.length > 0">
                                        <div v-for="result in results" :key="result.id" type="text" placeholder="Search...">
                                            <div @click="replace(result)" class="search-flex">
                                                <div @click="addParticipants(result)">{{result.name}} {{result.surname}}</div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            <div class="modal-item-wrapper" v-if="participantsDisplayed.length > 0">
                                <div class="participants" v-for="participant in participantsDisplayed" :key="participant.id">
                                    <div>{{participant.name}} {{participant.surname}}</div>
                                    <button class='red-modal-button' @click="sliceParticipants(participant)"><i class="fa fa-close"></i></button>
                                </div>
                                <span class="errors" v-if="errors.user_id">{{errors.user_id[0]}}</span>
                            </div>

                        </div>
                        <div class="modal-table-wrapper">
                            <table class="modal-table">
                                <thead>
                                    <tr>
                                        <th>Prticipant</th>
                                        <th>Participant type</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="user in toSubmit.participantsReactive" :key="user.id">
                                        <td>{{user.name}} {{user.surname}}</td>
                                        <td>{{user.type.name}}</td>
                                        <td><button class="initial red-modal-button" v-if="user.pivot.checked_in == 0 && user.type.name == 'Guest'" @click="checkIn(user)">Check in</button>
                                        <button class="red-modal-button" @click="removeOne(user.id)"><i class="fa fa-close"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="red-modal-button" @click="update()">Submit</button>
                        <button class="red-modal-button" @click="close()">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import moment from 'moment'
import moment_timezone from 'moment-timezone'

export default {
    props: {
        meeting: Object,
        modal: Boolean
    },
    computed: {
        token() {
            return this.$store.state.token
        }
    },
    mounted() {
    },
    data() {
        return {
            rooms: [],
            participantsDisplayed: [],
            participants: [],
            results: [],
            errors: [],
            query: '',
            toSubmit: {
                name: '',
                start_time: '',
                description: '',
                room_id: '',
                participantsReactive: [],
            },
            errors: [],
        }
    },
    watch: {
        query(after, before) {
            this.fetch()
        }
    },
    mounted() {
        this.fetchRooms()
        this.fillInputs()
    },
    computed: {
        token() {
            return this.$store.state.token
        }
    },
    methods: {
        reloadComponent() {
            this.$root.$emit('meeting-updated');
        },
        fillInputs() {
            this.toSubmit.name = this.meeting.name
            this.toSubmit.start_time = this.formatDate(this.meeting.start_time)
            this.toSubmit.description = this.meeting.description
            this.toSubmit.room_id = this.meeting.room_id
            this.toSubmit.participantsReactive = this.meeting.users
            console.log(this.toSubmit.participantsReactive)
        },
        fetchRooms() {
            axios.get('/api/rooms?token=' + this.token)
            .then(res => {
                this.rooms = res.data
            })
        },
        changeRoom(event) {
            const value = Number(event.target.value)
            switch(value) {
                case 1:
                    this.toSubmit.room_id = 1
                break;
                case 2:
                    this.toSubmit.room_id = 2
                break;
                case 3:
                    this.toSubmit.room_id = 3
                break;
                default:
                    this.toSubmit.room_id = Number(this.meeting.room_id)
            }
        },
        close() {
            this.$emit('close-info-modal')
        },
        fetch() {
            axios.get('/api/searchUsers?q=' + this.query, {
                headers: {
                    'Authorization': 'Bearer ' + this.token
                }
            })
                .then(response => this.results = response.data)
                .catch(error => {
                    console.log(error)
                })
        },
        addParticipants(participant) {
            const index = this.participants.findIndex(item => item.id == participant.id)
            if(this.participants.includes(participant.id)) {
                console.log('nemre')
            } else {
                this.participants.push(participant.id)
                this.participantsDisplayed.push(participant)
                console.log(participant)
            }
        },
        replace(user) {
            this.query = ''
            this.query = user.name + ' ' + user.surname
            this.query = ''

        },
        sliceParticipants(participant) {
            const index = this.participantsDisplayed.findIndex(item => item.id == participant.id)
            this.participants.splice(index, 1)
            console.log(index)
        },
        update() {
            this.$store.dispatch('updateMeeting', {
                id: this.meeting.id,
                participants: this.participants,
                name: this.meeting.name,
                start_time : this.toSubmit.start_time,
                room_id: this.toSubmit.room_id,
                description: this.toSubmit.description
            })
            .then(res => {
                this.close()
                this.$root.$emit('meeting-updated')
                this.toSubmit.participantsReactive.push(response.data.users)
            })
            .catch(err => {
                // console.log(err)
                this.errors = err.response.data.errors
            })
        },
        formatDate(date) {
            return moment(date).format('YYYY-MM-DDTHH:mm')
        },
        removeOne(userId) {
            axios.patch('/api/remove/meeting/'+ this.meeting.id +'/participant?token='+ this.token,{
                id: userId
            })
            .then(response => {
                const index = this.meeting.users.findIndex(item => item.id === userId)
                this.meeting.users.splice(index, 1)
            })
        },
        checkIn(user) {            
            this.$store.dispatch('checkIn', {
                id: user.id,
                planned: false,
            })
            axios.patch('/api/meeting/'+ this.meeting.id + '/checkInUser/' + user.id)

            .then(response => {
                this.toSubmit.participantsReactive.forEach(item => {
                    if(item.id == user.id) {
                        user.pivot.checked_in = 1
                    }
                })
            })
        }
    }
}
</script>

<style>

</style>
