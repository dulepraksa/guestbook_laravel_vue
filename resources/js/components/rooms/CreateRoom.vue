<template>
    <div>
        <div class="create-meeting-modal">
            <div class="modal-wrapper">
                <div class="modal-container">
                    <h4 class="modal-header">Manage rooms</h4>
                    <div class="modal-body">
                        <div class="modal-item">
                            <label for="name">Room name:</label>
                            <input id="name" type="text" v-model="name">
                            <span v-if="errors.name" class="errors"><i class="fa fa-warning"></i>{{errors.name[0]}}</span>
                        </div>
                        <div class="modal-item">
                            <ul class="modal-list">
                                <li class="modal-list-item" v-for="room in rooms" :key="room.id">
                                    <p class="m0">{{room.name}}</p>
                                    <span class="fa fa-trash cursor-pointer" @click="deleteRoom(room.id)">
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button @click="close" class="red-modal-button">Close</button>
                        <button @click="store" class="red-modal-button">Submit</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            name: '',
            rooms: [],
            errors: [],
        }
    },
    computed: {
        token() {
            return this.$store.state.token
        }
    },
    mounted() {
        this.fetchRooms()
    },
    methods: {
        close() {
            this.$emit('close-room-modal')
        },
        fetchRooms() {
            axios.get('/api/rooms?token=' + this.token)
            .then(response => {
                this.rooms = response.data
            })
        },
        store() {
            axios.post('/api/room?token=' + this.token,{
                name: this.name
            })
            .then(response => {
                this.rooms.push(response.data)
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
        deleteRoom(id) {
            axios.delete('/api/room/' + id + '?token=' + this.token)
            .then(response => {
                const index = this.rooms.indexOf(item => item.id  == id)
                this.rooms.splice(index, 1)
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        }
    }
}
</script>

<style>

</style>
