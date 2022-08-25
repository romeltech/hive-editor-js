<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline"
        >{{ pagetitle }} News &amp; Article</v-toolbar-title
      >
    </v-app-bar>

    <v-container class="py-2">
      <v-card flat>
        <v-card-title >
          <h4>{{ cardTitle }}</h4>
          <v-spacer></v-spacer>
          <v-btn v-if="pagetitle == 'edit'" @click="newPost" fab x-small><v-icon>mdi-plus-box</v-icon></v-btn>
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
                          <div class="d-flex">
                          <v-btn
                            v-if="pagetitle == 'edit'"
                            text
                            color="error"
                            small
                            @click="deleteData()"
                            >delete</v-btn
                          >
                          <v-spacer></v-spacer>
                          <v-btn
                            class="primary"
                            :disabled="!valid"
                            small
                            @click="submit"
                            >Save</v-btn
                          >
                          </div>
                        </v-card-text>
                      </v-card>
                      <v-divider></v-divider>
                      <v-card>
                        <v-card-text>
                          <small>Featured Image</small>
                          <v-img
                            style="border-radius: 4px"
                            :lazy-src="`${
                              $baseUrl + '/images/placeholder-image.png'
                            }`"
                            max-height="200"
                            width="200"
                            :aspect-ratio="1"
                            :src="`${
                              selectedImage && selectedImage.hasOwnProperty('id')
                                ? $baseUrl + '/file/' + selectedImage.path
                                : $baseUrl + '/images/placeholder-image.png'
                            }`"
                          >
                            <template v-slot:placeholder>
                              <v-row
                                class="fill-height ma-0"
                                align="center"
                                justify="center"
                              >
                                <v-progress-circular
                                  indeterminate
                                  color="grey lighten-5"
                                ></v-progress-circular>
                              </v-row>
                            </template>
                          </v-img>
                          <v-divider></v-divider>
                          <div
                            class="d-flex"
                            style="
                              width: 100%;
                              position: absolute;
                              bottom: 0;
                              top: auto;
                              right: auto;
                              left: 0;
                            "
                          >
                            <v-btn
                              v-show="
                               selectedImage && selectedImage.hasOwnProperty('id')
                                  ? true
                                  : false
                              "
                              width="50%"
                              color="rgba(245, 245, 245, .75)"
                              class="flex-grow-1 elevation-0 rounded-0"
                              @click="removeImage('thumbnail')"
                              >Remove</v-btn
                            >
                            <v-btn
                              width="50%"
                              color="rgba(245, 245, 245, .75)"
                              class="flex-grow-1 elevation-0 rounded-0"
                              @click="addImage('thumbnail')"
                              >{{
                               selectedImage && selectedImage.hasOwnProperty("id")
                                  ? "Replace"
                                  : "Upload"
                              }}</v-btn
                            >
                          </div>
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
       
      <v-row class="mt-1"  v-if="pagetitle == 'edit'">
        <v-col class="col-9">
            <v-card> 
              <v-card-text>
                <h5>Comment(s)</h5>
                <v-divider></v-divider>
              </v-card-text>
            </v-card>
        </v-col>
      </v-row>

    </v-container>
    <!-- actions and dialogs -->
    <snack-bar :snackbar-options="sbOptions"></snack-bar>
    <confirmation-dialog
      :conf-options="confOptions"
      @response="confResponse"
    ></confirmation-dialog>

    <v-dialog
      v-model="studioSettings.dialog"
      persistent
      width="1000"
      style="min-height: 400px"
    >
      <v-card>
        <Studio :studio-options="studioSettings" @responded="studioResponse" />
      </v-card>
    </v-dialog>
  </div>
</template>
<script>
import CKEditor from "@ckeditor/ckeditor5-vue2";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Studio from "../../admin/studio/Studio";
import {
  ValidationObserver,
  ValidationProvider,
} from "vee-validate/dist/vee-validate.full";

class UploadAdapter {
  constructor(loader) {
    this.loader = loader;
  }

  upload() {
    return this.loader.file.then((uploadedFile) => {
      return new Promise((resolve, reject) => {
        const data = new FormData();
        data.append("upload", uploadedFile);

        axios({
          url: "/d/admin/post-editor/upload",
          method: "post",
          data,
          headers: {
            "Content-Type": "multipart/form-data;",
          },
          withCredentials: false,
        })
          .then((response) => {
            if (response.data.result == "success") {
              resolve({
                default: response.data.url,
              });
            } else {
              reject(response.data.message);
            }
          })
          .catch((response) => {
            reject("Upload failed");
          });
      });
    });
  }

  abort() {}
}

export default {
  name: "MediaForm",
  components: {
    ValidationProvider,
    ValidationObserver,
    CKEditor,
    Studio,
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
      studioSettings: {
        dialog: false,
        multiSelect: false,
      },
      selectedImage: {},
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
           
          this.editorData = this.formObj.content;
          this.selectedImage = this.formObj.images[0];
        }
        this.loading = false;
      },
      deep: true,
    },
  },
  methods: {
    newPost: function(){
      this.$router.push({ name: "NewMedia" });
    },
    studioResponse: function (v) {
      this.studioSettings.dialog = v.dialog;
      this.selectedImage =
        v.images != null ? Object.assign({}, v.images[0]) : {};
    },
    removeImage: function () {
      this.selectedImage = [];
    },
    addImage: function () {
      this.studioSettings.dialog = true;
    },
    uploader(editor) {
      editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
      };
    },
    submit: function () {
      this.loading = true;
      let images = this.selectedImage.hasOwnProperty("id")
        ? this.selectedImage.id
        : null;

      this.formObj.content = this.editorData;
      this.formObj.type = "post";
      // Set status value
      this.formObj.status = this.statusSwitch == true ? "active" : "draft";
      let dataForm = { data: this.formObj };
      if (this.formObj.id) {
        let postID = this.formObj.id;
        let bdata = this.formObj;
        delete bdata["created_at"];
        delete bdata["updated_at"];
        delete bdata["images"];
        delete bdata["id"];
        delete bdata["user_id"];
        dataForm = { data: bdata, image: images, id: postID };
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
              this.removeImage();
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