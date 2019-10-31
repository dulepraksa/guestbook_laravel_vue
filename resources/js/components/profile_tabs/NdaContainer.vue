<template>
    <div class="contracts-tab">
        <div v-if="contracts.length > 0" class="contracts-list" v-for="contract in contracts" :key="contract.id">
            <div for=""><label for="">{{contract.name}}</label><button class="red-modal-button cursor-pointer" @click="download(contract.id, contract)"><i class="fa fa-download"></i></button> </div>
        </div>
        <div class="contracts-list" v-if="contracts.length == 0">
            <h4>This user has no contracts!</h4>
        </div>
        <div v-if="userRole[0].name == 'Secretary' || userRole[0] == 'GDPR-Admin'" class="contracts-tab-add">
            <label for="">Contract name:</label>
            <input type="text" name="" id="" v-model="name" />
            <label class="inputfile-label" for="file"> <span class="fa fa-upload"></span>Choose file</label>
            <button class="red-modal-button cursor-pointer" @click="addContract">Upload</button>
            <input class="inputfile" ref="file" id="file" name="file" type="file" @change="fileChanged">
        </div>
    </div>
</template>

<script>
export default {
    props: {
        userId: String
    },
    computed: {
        contracts() {
            return this.$store.getters.getContracts
        },
        userRole() {
            return this.$store.state.user_roles
        },
        token() {
            return this.$store.state.token
        }
    },
    created() {
        this.$store.dispatch('allContracts', this.userId)
    },
    data() {
        return {
            file: '',
            name: '',
            fileName: ''
        }
    },
    methods: {
        fileChanged(e) {
            console.log(e.target.files[0])
            let fileReader = new FileReader()
            fileReader.readAsDataURL(e.target.files[0])
            fileReader.onload = (e) => {
                this.file = e.target.result       
            }
            this.fileName = e.target.files[0].name
            this.name = this.fileName
        },
        forceFileDownload(response, contract){
            console.log(response, contract)
            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement('a')
            link.href = url
            link.setAttribute('download', contract.contract, contract.sub_folder)
            document.body.appendChild(link)
            link.click()
    },
    
        addContract() {
            this.$store.dispatch('addContract', {
                user_id: this.userId,
                contract: this.file,
                name: this.name
            })
            .then(response => {
                this.name = ''
            })
        },
        download(id, contract) {
            if(confirm(`Are you sure you want to download ${contract.name}?`)){
                axios.get('/api/contract/' + id + '/' + contract + '?token=' + this.token, {
                    responseType: 'arraybuffer'
                })
                    .then(response => {
                        this.forceFileDownload(response, contract)
                    })
                    .catch(error => {
                        console.log(error)
                    })
            }
        },
        checkRole() {
            if(userRole[0].name =='GDPR-Admin' || userRole[0].name =='Secretary') {
                return true
            } else {
                return false
            }
        }
    }
}
</script>
