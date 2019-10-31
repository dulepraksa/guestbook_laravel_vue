<template>
    <div>
        <table class="guest-visits-table">       
            <thead>
                <tr>
                    <th>Time</th>
                    <th>Date</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="meeting in meetings" :key="meeting.id">
                    <td>{{timeReformat(meeting.start_time)}}</td>
                    <td>{{dateReformat(meeting.start_time)}}</td>
                    <td>{{meeting.description}}</td>
                </tr>     
            </tbody>
            <tfoot>
                <div>
                    <pagination
                    :meta="meta"
                    v-on:pagination:switched="fetchMeetings"
                    />
                </div>
            </tfoot>     
        </table>
    </div>
</template>

<script>
import pagination from '../addtitonal_components/Pagination'

function zeroPadding(num, digit) {
    var zero = '';
    for(var i = 0; i < digit; i++) {
        zero += '0';
    }
    return (zero + num).slice(-digit);
}


export default {
    props: {
        user: Object
    },
    components: {
        pagination
    },
    computed: {
        authUser() {
            return this.$store.state.user
        },
        meta() {
            return this.$store.state.meetingsMeta
        },
        meetings() {
            return this.$store.state.meetingsWithUsers
        },
    },
    mounted(){
        this.fetchMeetings()
    },
    methods: {
        timeReformat(timeString) {
            if (timeString != null) {
                let time = new Date(timeString);
                let newTime = zeroPadding(time.getHours(),2) + ':' + zeroPadding(time.getMinutes(),2)
                return newTime
            }
        },
        dateReformat(timeString) {
            let date = new Date(timeString);
            let newDate = zeroPadding(date.getDate(), 2 ) + '.' + zeroPadding(date.getMonth()+1, 2) + '.' + zeroPadding(date.getFullYear(), 4)
            return newDate
        },
        fetchMeetings(page = this.$route.query.params) {
            this.$store.dispatch('fetchUserMeetings', this.user.id, page )
        }
    }
}
</script>

<style>

</style>
