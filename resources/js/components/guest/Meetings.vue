<template>
    <div class="home-meetings">
        <div class="home-meetings-header">
            <div class="title">
                <h3>Meetings</h3>
            </div>
            <div v-if="loggedIn">
                <a class="cursor-pointer open-modal-a"  @click="closeModal">Create a meeting</a>
            </div>
            <div v-else>

            </div>
            <div class="filters">
                <a :class="[ filter === 'today' ? 'active' : '' ]" @click="todayMeetings">Today</a>
                <a :class="[ filter === 'thisWeek' ? 'active' : '' ]" @click="thisWeekMeetings">This Week</a>
                <a :class="[ filter === 'thisMonth' ? 'active' : '' ]" @click="thisMonthMeetins">This Month</a>
            </div>
        </div>
        <div class="table-div no-shadow">
            <table class="guest-list-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Time</th>
                        <th>Date</th>
                        <th>Description</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="meeting in meetings" :key="meeting.id" @click="openMeetingInfo(meeting)">
                        <td>{{meeting.name}}</td>
                        <td>{{timeReformat(meeting.start_time)}}</td>
                        <td>{{dateReformat(meeting.start_time)}}</td>                        
                        <td>{{meeting.description}}</td>
                    </tr>     
                </tbody>
                <tfoot>
                    <div>
                        
                    </div>
                    <div>
                        <pagination :meta="meta" v-on:pagination:switched='promeni'></pagination>
                    </div>
                </tfoot>
            </table>
        </div>
        <div>
            <meetings-modal  @close-modal="closeModal" :modal="modal"></meetings-modal>
            <meeting-info-modal  @close-info-modal="closeInfoModal" v-if="infoModal" :meeting="meetingData"/>
        </div>
    </div>
</template>

<script>
import MeetingsModal from '../addtitonal_components/MeetingsModal'
import pagination from '../addtitonal_components/Pagination'
import MeetingInfoModal from '../addtitonal_components/MeetingInfoModal'
import moment from 'moment'
import momenttimezone from 'moment-timezone'

function zeroPadding(num, digit) {
    var zero = '';
    for(var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}

export default {
    components: {
        MeetingsModal,
        pagination,
        MeetingInfoModal
    },
    data() {
        return {
            infoModal: false,
            editModal: false,
            meetingData: {},
            modal: false,
            filter: 'today',
            meetingForEditing: {
                start_time: '',
                description: '',
                userId: '',
                guestId: ''
            },
            rooms: [],
            roomId: 1
        }
    },
    created() {
        this.$store.dispatch('todayMeetings')
    },
    mounted() {
        this.fetchRooms()
        this.$root.$on('meeting-updated', () =>{
            this.$store.dispatch('todayMeetings')
        })
    },
    computed: {
        meetings() {
            return this.$store.getters.allMeetings
        },
        meta() {
            return this.$store.state.meetingsMeta
        },
        loggedIn() {
            return this.$store.getters.loggedIn
        }
    },
    methods: {

        fetchRooms() {
            axios.get('/api/rooms')
            .then(res => {
                this.rooms = res.data
            })
        },

        closeModal() {
            this.modal =! this.modal
        },

        promeni(page = 1) {
            if (this.filter == 'today'){
                this.$store.dispatch('todayMeetings', page)
            }
            if(this.filter == 'thisWeek') {
                this.$store.dispatch('thisWeekMeetings', page)
            }
            if(this.filter == 'thisMonth') {
                this.$store.dispatch('thisMonthMeetings', page)
            }
        },

        todayMeetings(page = this.$route.query.params) {
            this.filter = 'today'
            this.$store.dispatch('todayMeetings', page)
        },
        thisWeekMeetings(page = this.$route.query.params){
            this.filter = 'thisWeek'
            this.$store.dispatch('thisWeekMeetings', page)
        },
        thisMonthMeetins(page = this.$route.query.params){
            this.filter = 'thisMonth'
            this.$store.dispatch('thisMonthMeetings', page)                
        },

        changeRoom(event) {
            const value = Number(event.target.value)
            switch(value) {
                case 1:
                    this.roomId = 1
                break;
                case 2:
                    this.roomId = 2
                break;
                case 3:
                    this.roomId = 3
                break;
                default:
                    this.roomId = Number(this.meeting.room_id)
            }
        },

        timeReformat(time) {
            return moment(time).tz('Europe/Belgrade').format('HH:mm')
        },

        dateReformat(date) {
            return moment(date).tz('Europe/Belgrade').format('Y.MM.DD')
        },

        openEditingModal(meeting) {
            this.meetingForEditing.id = meeting.id
            this.meetingForEditing.start_time = this.formatDate(meeting.start_time)
            this.meetingForEditing.description = meeting.description
            this.roomId = meeting.room.id
            this.editModal = true
        },

        formatDate(date) {
            return moment(date).format('YYYY-MM-DDTHH:mm')
        },

        closeEditingModal() {
            this.meetingForEditing.id = ''
            this.meetingForEditing.roomId = ''
            this.meetingForEditing.start_time = ''
            this.meetingForEditing.description = ''
            this.editModal = false
        },
        updateMeeting() {
            this.$store.dispatch('updateMeeting', {
                id: this.meetingForEditing.id,
                user_id: this.meetingForEditing.userId,
                start_time: this.formatDate(this.meetingForEditing.start_time),
                description: this.meetingForEditing.description,
            })
        },
        openMeetingInfo(meeting) {
            if(this.loggedIn) {
                this.infoModal = true
                this.meetingData = meeting
            } else {
                this.infoModal = false
            }
        },
        closeInfoModal() {
            this.infoModal = false
        }
    }
}
</script>
