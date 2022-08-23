<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline"
        >{{ pagetitle }} News &amp; Article</v-toolbar-title
      >
    </v-app-bar>

    <v-container class="py-2">
      <v-card flat>
        <v-card-title v-if="pagetitle !== 'edit'">
          <h4>{{ cardTitle }}</h4>
        </v-card-title>
        <v-card-text>
          <v-row>
            <div class="col-12 col-md-12">
              <ValidationObserver ref="user_form_observer" v-slot="{ valid }">
                <v-form ref="form">
                  <v-row>
                    <v-col md="9" sm="12">
                      <v-card :loading="loading">
                        <v-card-text>
                          <v-row>
                            <v-col md="12">
                              <ValidationProvider
                                v-slot="{ errors }"
                                rules="required"
                                name="title"
                              >
                                <v-text-field
                                  dense
                                  v-model="formObj.title"
                                  label="Enter Title*"
                                  outlined
                                  required
                                  hide-details
                                  :error-messages="errors"
                                ></v-text-field>
                              </ValidationProvider>
                            </v-col>
                            <v-col md="12">
                              <ckeditor
                                :editor="editor"
                                v-model="editorData"
                                :config="editorConfig"
                              ></ckeditor>
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
                    </v-col>
                    <v-col md="3" sm="12">
                      <v-card :loading="loading">
                        <v-card-text>
                          <v-switch
                            style="max-width: 120px"
                            v-model="statusSwitch"
                            :color="`${
                              statusSwitch == true ? 'success' : 'grey'
                            }`"
                            :label="`${
                              statusSwitch == true ? 'Active' : 'Draft'
                            }`"
                          ></v-switch>
                          <v-btn
                            v-if="pagetitle == 'edit'"
                            text
                            color="error"
                            @click="deleteData()"
                            >delete</v-btn
                          >
                          <v-spacer></v-spacer>
                          <v-btn
                            class="primary"
                            :disabled="!valid"
                            @click="submit"
                            >Save</v-btn
                          >
                        </v-card-text>
                      </v-card>
                    </v-col>
                  </v-row>
                </v-form>
              </ValidationObserver>
            </div>
          </v-row>
        </v-card-text>
      </v-card>
    </v-container>
    <!-- actions and dialogs -->
    <snack-bar :snackbar-options="sbOptions"></snack-bar>
    <confirmation-dialog
      :conf-options="confOptions"
      @response="confResponse"
    ></confirmation-dialog>
  </div>
</template>
<script>
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
 
import {
  ValidationObserver,
  ValidationProvider,
} from "vee-validate/dist/vee-validate.full";
 
class UploadAdapter {
        constructor(loader) {
            this.loader = loader;
        }

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

        abort() {
        }
    }

export default {
  name: "MediaForm",
  components: {
    ValidationProvider,
    ValidationObserver,
   CKEditor,
  },
  props: {
    objectdata: {
      type: Object,
      default: null,
    },
    pagetitle: {
      type: String,
      default: "new",
    },
  },
  data() {
    return {
      statusSwitch: true,
      rolesArray: ["admin", "staff"],
      actionSave: this.pagetitle,
      cardTitle: "News & Articles",
      formObj: {},
      newStatus: "",
      search: "",
      // ui

      sbOptions: {},
      filterObj: {},
      confOptions: {},
      loading: this.objectdata ? true : false,
      editor: ClassicEditor,
      editorData: "",
      editorConfig: {
        toolbar: [
          "heading",
          "|",
          "bold",
          "italic",
          "link",
          "bulletedList",
          "numberedList",
          "|",
          "insertTable",
          "|",
          "imageUpload",
          "mediaEmbed",
          "|",
          "undo",
          "redo",
        ],
        table: {
          toolbar: ["tableColumn", "tableRow", "mergeTableCells"],
        },
        extraPlugins: [this.uploader],
      },
    };
  },
  watch: {
    objectdata: {
      handler(val, oldVal) {
        if (val != oldVal) {
          this.formObj = Object.assign({}, val);
          this.statusSwitch = val.status == "active" ? true : false;
        }
        this.loading = false;
      },
      deep: true,
    },
  },
  methods: {
    uploader(editor) {
      editor.plugins.get( 'FileRepository' ).createUploadAdapter = ( loader ) => {
            // Configure the URL to the upload script in your back-end here!
            return new UploadAdapter( loader );
        };
    },
    submit() {
      this.loading = true;

      // Set status value
      this.formObj.status = this.statusSwitch == true ? "active" : "draft";
      let dataForm = { data: this.formObj };

      if (this.formObj.id) {
        let bdata = this.formObj;
        delete bdata["created_at"];
        delete bdata["updated_at"];
        delete bdata["km"];
        delete bdata["year"];
        delete bdata["chassis_no"];
        dataForm = { data: bdata };
      }

      // Send data to save
      axios
        .post("/d/admin/media/save", dataForm)
        .then((response) => {
          this.sbOptions = {
            status: true,
            type: "success",
            text: "Data has been saved",
          };
          if (this.pagetitle == "edit") {
            this.$emit("saved", true);
          } else {
            this.$nextTick(() => {
              this.loading = false;
              this.formObj = {};
              this.$refs.user_form_observer.reset();
              this.$router.push({ name: "Medias" });
            });
          }
        })
        .catch((err) => {
          this.loading = false;
          this.sbOptions = {
            status: true,
            type: "error",
            text: "Error saving data",
          };
        });
    },
    deleteData() {
      this.confOptions = {
        status: true,
        title: "Confirm",
        msg: "Please confirm that you want to delete this data.",
        btnTitle: "delete",
        action: "delete",
      };
    },
    confResponse(value) {
      if (value == true) {
        axios
          .get("/d/admin/media/delete/" + this.formObj.id)
          .then((response) => {
            this.sbOptions = {
              status: true,
              type: "success",
              text: "Data has been deleted",
            };
            setTimeout(() => {
              this.$router.push({ name: "Medias" });
            }, 800);
          })
          .catch((err) => {
            console.log(err.response.data);
            this.loading = false;
            this.sbOptions = {
              status: true,
              type: "error",
              text: err.response.data.message,
            };
          });
      }
    },
  },
};
</script>
