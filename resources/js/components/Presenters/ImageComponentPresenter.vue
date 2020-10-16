<template>
    <div class="flex-container">
        <div class="row">
            <div class="col-12">
                <div class="col-8 float-right">
                    <div class="card">
                        <div class="card-header">Preview</div> 
                        <div class="card-body"> <img :src="selectedDisplayImage"> </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">Image Form</div>
                        <div class="card-body">
                            <form >
                                <div class="form-group">
                                    <label>Image</label>
                                    <input v-if="uploadReady" type="file" class="form-control-file" @change="$emit('update-file', $event.target.files[0] )">
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea :value="description" @change="$emit('update-description', $event.target.value)" class="form-control" rows="3"></textarea>
                                </div>
                            </form>
                            <button type="submit" class="btn btn-primary mb-2" @click="$emit('submit-form')" >Submit</button>
                        </div>
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">List of Images</div>
                        <div class="card-body"> 
                            <div class="form-group">
                                <select class="form-control" v-model="selectedImage" @change="$emit('choose-image', selectedImage)">
                                    <template v-for="value in allImages">
                                        <option :key="value.id" :value="value.id"> {{value.file_name }} </option>
                                        </template>
                                </select>
                            </div>
                            <button v-if="uploadButton" @click="$emit('upload-to-s3', selectedImage)"> Upload to S3 </button> 
                        </div> 
                    </div>
                </div>
                <div class="col-4">
                    <div class="card">
                        <div class="card-header">Size</div>
                        <div class="card-body"> 
                            <div class="form-check">
                                <input 
                                    @change="$emit('get-resize', { 'imageSize' : imageSizeRadio, 'selectedImage' : selectedImage })"
                                    v-model="imageSizeRadio" class="form-check-input" type="radio" value="0">
                                <label class="form-check-label">
                                    Thumbnail
                                </label>
                            </div>
                            <div class="form-check">
                                <input  
                                    @change="$emit('get-resize', { 'imageSize' : imageSizeRadio, 'selectedImage' : selectedImage })"
                                    v-model="imageSizeRadio" class="form-check-input" type="radio" value="1">
                                <label class="form-check-label">
                                    Small
                                </label>
                            </div>
                            <div class="form-check">
                                <input  
                                    @change="$emit('get-resize', { 'imageSize' : imageSizeRadio, 'selectedImage' : selectedImage })"
                                    v-model="imageSizeRadio" class="form-check-input" type="radio" value="2">
                                <label class="form-check-label" >
                                    Big Image
                                </label>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props : ['allImages','description',"uploadReady","selectedDisplayImage"],
        data() {
            return {
               descriptionPlaceHolder : this.description,
               selectedImage : '',
               imageSizeRadio: '',
               uploadButton : false, 
            }
        },
        watch: {
            selectedImage : function(val){
                let image = this.allImages.filter(a => a.id === this.selectedImage)[0];
                this.imageSizeRadio = '';
                if(image.is_uploaded_to_s3 === 0) {   
                    this.uploadButton = true;
                } else {
                    this.uploadButton = false;
                }
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>