<script>
 export default class UploadAdapter {
        constructor( loader ) {
            // The file loader instance to use during the upload.
            this.loader = loader;
        }
    
        // Starts the upload process.
        upload() {
            return this.loader.file
                .then( uploadedFile => {
                    return new Promise( ( resolve, reject ) => {
                    const data = new FormData();
                    data.append( 'upload', uploadedFile );

                    axios( {
                        url: '/d/admin/post-editor/upload',
                        method: 'post',
                        data,
                        headers: {
                            'Content-Type': 'multipart/form-data;'
                        },
                        withCredentials: false
                    } ).then( response => {
                        if ( response.data.result == 'success' ) {
                            resolve( {
                                default: response.data.url
                            } );
                        } else {
                            reject( response.data.message );
                        }
                    } ).catch( response => {
                        reject( 'Upload failed' );
                    } );

                } );
            } );
        }
    
        // Aborts the upload process.
        abort() {
            if ( this.xhr ) {
                this.xhr.abort();
            }
        }
    
 
    }
</script>