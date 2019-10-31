<template>
    <div class="create-meeting-modal" v-if="modal == true">
        <div class="modal-wrapper">
            <div class="modal-container">
                <div class="modal-header">
                    <h3>Create Meeting</h3>
                </div>
                <div class="modal-body">
                    <div class="modal-item">
                        <label>Start time:</label>
                        <input v-model="start_time" type="datetime-local">
                        <span class="errors" v-if="errors.start_time"><i class="fa fa-warning"></i>{{errors.start_time[0]}}</span>
                    </div>
                    <div class="modal-item">
                        <label>Meeting name:</label>
                        <input v-model="name" type="input">
                        <span class="errors" v-if="errors.name"><i class="fa fa-warning"></i>{{errors.name[0]}}</span>
                    </div>
                    <div class="input-button">
                        <select @change="changeRoom($event)" style="padding: 3px;" type="text">
                            <option v-for="room in rooms" :key="room.id" :value="room.id">{{room.name}}</option>
                        </select>
                    </div>
                    <div class="modal-item">
                        <label>Description:</label>
                        <input v-model="description" type="text" name="" id="">
                        <span class="errors" v-if="errors.description"><i class="fa fa-warning"></i>{{errors.description[0]}}</span>
                    </div>
                    <div class="modal-footer">
                        <button class="red-modal-button" @click="createMeeting">Submit</button>
                        <button class="red-modal-button" @click="Close">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props:{
        modal: Boolean,
    },
    watch: {
        queryUsers(after, before) {
            this.fetchUsers()
        }
    },
    computed:{
        token() {
            return this.$store.state.token
        }
    },
    mounted() {
        this.fetchRooms()
    },
    data() {
        return {
            start_time: '',
            room_id: 1,
            description: '',
            guests: [],
            users: [],
            queryGuests: null,
            queryUsers: null,
            picked: '',
            userId: '',
            guestId: '',
            name: '',
            errors: [],
            rooms: [],
        }
    },
    methods:{
        Close() {
            this.$emit('close-modal')
            this.start_time =  ''
            this.room_id = '' 
            this.description = ''
            this.users = []
            this.queryUsers = null
            this.picked =  '',
            this.name = ''
            this.userId =  ''
            this.errors = []
            
        },
        fetchRooms() {
            axios.get('/api/rooms')
            .then(res => {
                this.rooms = res.data
            })
        },
        fetchUsers() {
            axios.get('/api/searchUsers?q=' + this.queryUsers, {
                headers: {
                    'Authorization': 'Bearer' + this.token
                }
            })
                .then(response => this.users = response.data)
                .then(response => console.log(response))
                .catch(error => {console.log(error)});
        },
        chooseUser(uid, uname, usurname) {
            console.log( uid, uname, usurname)
            this.queryUsers = uname + ' ' + usurname
            this.userId = uid
        },
        createMeeting() {
            console.log(this.room_id)
            
            this.errors = [];
            this.$store.dispatch('createMeeting',{
                room_id: this.room_id,
                name: this.name,
                description: this.description,
                start_time: this.start_time
            })
            .then(response => {
                this.start_time =  ''
                this.name = ''
                this.room_id = '' 
                this.description = ''
                this.guests = []
                this.users = []
                this.queryUsers = null
                this.picked =  ''
                this.userId =  ''
                this.guestId = ''
                this.errors = []
                this.Close()
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
        changeRoom(event) {
            const value = Number(event.target.value)
            switch(value) {
                case 1:
                    this.room_id = 1
                    console.log(this.room_id)
                break;
                case 2:
                    this.room_id = 2
                    console.log(this.room_id)

                break;
                case 3:
                    this.room_id = 3
                    console.log(this.room_id)
                break;
                default:
                    this.room_id = 1
            }
        },
    }
}
</script>

<style>

</style>
