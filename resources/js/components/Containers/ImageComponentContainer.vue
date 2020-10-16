<template>
    <div>
        <image-presenter
            @update-description="updateDescription"
            @update-file="updateFile"
            @submit-form="submitForm"
            @get-resize="getResize"
            @choose-image="chooseImage"
            @upload-to-s3="uploadToS3"
            :description="description"
            :upload-ready="uploadReady"
            :all-images="allImages"
            :selected-display-image="selectedDisplayImage"
        >

        </image-presenter>
    </div>
</template>

<script>
    export default {
        props: ["allImages"],
        data() {
            return {
                selectedDisplayImage : '',
                description : '',
                file : null,
                uploadReady : true,
            }
        },
        mounted() {
            console.log('Component Container mounted.')
        },
        methods: {
            resetForm() {
                console.log('resetForm');
                this.description = '',
                this.file = null;
                this.uploadReady = false
                this.$nextTick(() => {
                    this.uploadReady = true
                })
            },

            updateDescription(description) {
                this.description = description;
            },

            updateFile(File){
                this.file = File;
            },

            chooseImage(data){
                let image = this.allImages.filter(a => a.id === data)[0];
                this.displayImage(image);
            },

            displayImage(image) {
                this.selectedDisplayImage = 'data:image/' + image.file_extenstion + ';base64, ' + image.image;
            },

            submitForm() {
                const formData = new FormData();
                formData.append('file', this.file, this.file.name);
                formData.append('description', this.description);
                axios.post('/api/imageUpload', formData, {
                }).then((res) => {
                    this.resetForm();
                    this.allImages.push(res.data.imageData);
                    console.log(res)
                })
            },

            getResize(data) {
                axios.get('/api/getResize?id=' + data.selectedImage + '&size=' + data.imageSize).then((res) => {
                        console.log(res)
                        this.displayImage(res.data);
                    })
                },

            uploadToS3(id) {
                let payload = {
                    id : id
                };
                axios.post('/api/uploadToS3', payload, {
                }).then((res) => {
                    let index = this.allImages.findIndex(a => a.id === id);
                    if(res.data.success == true) {
                        this.allImages[index] = res.data.imageData;
                    }
                    console.log(res)
                })
            } 
        }
    }
</script>