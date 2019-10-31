<template>
    <div class="create-meeting-modal">
        <div class="modal-wrapper">
            <div class="modal-container">
                <h4 class="modal-header">Manage types</h4>
                <div class="modal-body">
                    <div class="modal-item">
                        <label for="name">Type name:</label>
                        <input id="name" type="text" v-model="name">
                        <span v-if="errors.name" class="errors"><i class="fa fa-warning"></i>{{errors.name[0]}}</span>
                    </div>
                    <div class="modal-item">
                        <ul class="modal-list">
                            <li class="modal-list-item" v-for="type in types" :key="type.id">
                                <p class="m0">{{type.name}}</p>
                                <span class="fa fa-trash cursor-pointer" @click="deleteType(type.id)">
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button @click="close" class="red-modal-button">Close</button>
                    <button @click="storeType" class="red-modal-button">Submit</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        types: Array
    },
    data() {
        return {
            name: '',
            errors: []
        }
    },
    methods: {
        close() {
            this.$emit('close-type-modal');
        },
        storeType() {
            axios.post('/api/type', {
                name: this.name
            })
            .then(response => {
                this.types.push(response.data)
            })
            .catch(error => {
                this.errors = error.response.data.errors
            })
        },
        deleteType(id) {
            axios.delete('/api/type/' + id)
            .then(response => {
                const index = this.types.indexOf(item => item.id == id)
                this.types.splice(index, 1)
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
