<template>
  <div>
    <v-app-bar color="white" dense class="elevation-0">
      <v-toolbar-title class="overline"
        >{{ pagetitle }} News &amp; Article</v-toolbar-title
      >
    </v-app-bar>

    <v-container class="py-2">
      <v-card flat>
        <v-card-title>
          <h4>{{ cardTitle }}</h4>
          <v-spacer></v-spacer>
          <v-btn v-if="pagetitle == 'edit'" @click="newPost" fab x-small
            ><v-icon>mdi-plus-box</v-icon></v-btn
          >
        </v-card-title>
        <v-card-text>
          <v-row>
            <div class="col-12 col-md-12">
              <ValidationObserver ref="user_form_observer" v-slot="{ valid }">
                <v-form ref="form">
                  <v-row>
                    <v-col md="9" sm="12">
                      <v-card
                        class="mb-2"
                        v-if="typePost == 'event' || typePost == 'poll'"
                      >
                        <v-card-text>
                          <v-row>
                            <v-col cols="12" sm="6" md="4">
                              <v-menu
                                v-model="startDate"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    v-model="eventDate.date_start"
                                    label="Start Date"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                  ></v-text-field>
                                </template>
                                <v-date-picker
                                  v-model="eventDate.date_start"
                                  @input="startDate = false"
                                ></v-date-picker>
                              </v-menu>
                            </v-col>
                            <v-col cols="12" sm="6" md="4">
                              <v-menu
                                v-model="endDate"
                                :close-on-content-click="false"
                                :nudge-right="40"
                                transition="scale-transition"
                                offset-y
                                min-width="auto"
                              >
                                <template v-slot:activator="{ on, attrs }">
                                  <v-text-field
                                    v-model="eventDate.date_end"
                                    label="End Date"
                                    prepend-icon="mdi-calendar"
                                    readonly
                                    v-bind="attrs"
                                    v-on="on"
                                  ></v-text-field>
                                </template>
                                <v-date-picker
                                  v-model="eventDate.date_end"
                                  @input="endDate = false"
                                ></v-date-picker>
                              </v-menu>
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
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
                              <ContentEditor :prop-content="formObj.content" @changed="changeResponse" />
                              <!-- <ckeditor
                                :editor="editor"
                                v-model="editorData"
                                :config="editorConfig"
                              ></ckeditor> -->
                            </v-col>
                          </v-row>
                        </v-card-text>
                      </v-card>
                      <v-card class="mt-2" v-if="typePost == 'poll'">
                        <v-card-text>
                          <h5>Enter choices here</h5>
                          <v-divider></v-divider>
                          <v-row class="my-2">
                            <v-col
                              md="12"
                              class="py-1"
                              v-for="(item, index) in newChoices"
                              :key="index"
                            >
                              <v-text-field
                                v-model="newChoices[index].content"
                                append-outer-icon="mdi-trash-can-outline"
                                outlined
                                clear-icon="mdi-close-circle"
                                clearable
                                dense
                                label="Enter choice"
                                type="text"
                                hide-details
                                @click:append-outer="deleteChoice(index)"
                                @click:clear="clearChoice(index)"
                              ></v-text-field>
                            </v-col>
                            <v-col md="12">
                              <v-switch
                                v-model="choiceTextBox"
                                color="success"
                                class="mt-0"
                                hide-details
                                dense
                                label="enable if you want to have textbox as a choice"
                              ></v-switch>
                              <v-btn
                                @click="addNewChoice"
                                small
                                class="primary my-2"
                                >Add New Choice</v-btn
                              >
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
                              class="primary"
                              :disabled="!valid"
                              small
                              @click="submit"
                              >Save</v-btn
                            >
                            <v-spacer></v-spacer>

                            <v-btn
                              v-if="pagetitle == 'edit'"
                              text
                              color="error"
                              small
                              @click="deleteData()"
                              >delete</v-btn
                            >
                          </div>
                        </v-card-text>
                      </v-card>
                      <v-divider></v-divider>
                      <UploadImage
                        :selectedImage="selectedImage"
                        @selected="imageResponse"
                      />
                      <v-divider></v-divider>
                      <v-card :loading="loading">
                        <v-card-text>
                          <small>Categories</small>
                          <v-row>
                            <v-col cols="12" sm="12" md="12">
                              <div v-for="item in categoryList" :key="item.id">
                                <v-checkbox
                                  dense
                                  v-model="categories"
                                  :label="item.title"
                                  :value="item.id"
                                  hide-details
                                ></v-checkbox>
                              </div>
                            </v-col>
                          </v-row>
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
      <v-row class="mt-1" v-if="pagetitle == 'edit'">
        <v-col
          sm="12"
          xs="12"
          :class="`${posttype == 'poll' ? 'col-md-6' : 'col-md-9'}`"
        >
          <v-card>
            <v-card-text>
              <h5>
                Comments ( {{ formObj.comments && formObj.comments.length }} )
              </h5>
              <v-divider></v-divider>
              <div
                class="my-2"
                v-if="formObj.comments && formObj.comments.length > 0"
              >
                <div
                  v-for="itz in formObj.comments"
                  :key="itz.id"
                  class="post-comment-section"
                >
                  <div
                    :class="`${
                      itz.status == 'active' ? 'primary--text' : 'warning--text'
                    } `"
                  >
                    {{ itz.profile.fullname }} -
                    {{ itz.content ? itz.content : "" }}
                    <span class="float-right">{{
                      dateTimeFormatter(itz.created_at)
                    }}</span>
                  </div>

                  <div class="comment-status-change">
                    <v-btn
                      v-if="itz.status == 'active'"
                      x-small
                      class="warning"
                      @click="handleCommentStatus(itz, 'draft')"
                      >Draft</v-btn
                    >
                    <v-btn
                      v-else
                      x-small
                      color="info"
                      @click="handleCommentStatus(itz, 'active')"
                      >Approve</v-btn
                    >
                  </div>
                </div>
              </div>
            </v-card-text>
          </v-card>
        </v-col>
        <v-col
          sm="12"
          xs="12"
          v-if="posttype == 'poll'"
          :class="`${posttype == 'poll' ? 'col-md-6' : 'col-md-9'}`"
        >
          <v-card>
            <v-card-text>
              <h5>Polls ( {{ formObj.poll_answer_count }} )</h5>
              <v-divider></v-divider>
              <div
                class="my-2"
                v-if="formObj.poll_answer && formObj.poll_answer.length > 0"
              >
                <div
                  v-for="itz in formObj.poll_answer"
                  :key="itz.id"
                  class="primary--text"
                >
                  {{ itz.profile.fullname }} -
                  {{
                    itz.content
                      ? itz.content
                      : itz.choices
                      ? itz.choices.content
                      : "-"
                  }}
                </div>
              </div>
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
  </div>
</template>
<script>
import ContentEditor from "../ui/content/editor/ContentEditor";

//import HtmlEmbed from '@ckeditor/ckeditor5-html-embed/src/htmlembed';
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import UploadImage from "../studio/UploadImage";
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
              console.log(response.data);
              resolve({
                default: "/file/" + response.data.filename,
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

    UploadImage,

    ContentEditor,
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
    newurl: {
      type: String,
      default: "",
    },
    deleteurl: {
      type: String,
      default: "",
    },
    redirectname: {
      type: String,
      default: "",
    },
    redirectedit: {
      type: String,
      default: "",
    },
    redirectnew: {
      type: String,
      default: "",
    },
    headertitle: {
      type: String,
      default: "",
    },
    posttype: {
      type: String,
      default: "",
    },
  },
  data() {
    return {
      categories: [],
      categoryList: [],
      statusSwitch: true,
      actionSave: this.pagetitle,
      URLadd: this.newurl,
      URLdelete: this.deleteurl,
      deleteRedirect: this.redirectname,
      editRedirect: this.redirectedit,
      newRedirect: this.redirectnew,
      cardTitle: this.headertitle,
      typePost: this.posttype,
      formObj: {},
      // ui

      eventDate: {
        date_start: new Date(
          Date.now() - new Date().getTimezoneOffset() * 60000
        )
          .toISOString()
          .substr(0, 10),
        date_end: new Date(Date.now() - new Date().getTimezoneOffset() * 60000)
          .toISOString()
          .substr(0, 10),
      },
      endDate: false,
      startDate: false,
      sbOptions: {},
      filterObj: {},
      confOptions: {},
      loading: this.objectdata ? true : false,
      editor: ClassicEditor,
      editorData: "",
      editorConfig: {
        extraPlugins: [this.uploader],
      },
      // Poll choices
      newChoices: [],
      choiceTextBox: false,

      selectedImage: {},
      editorContent: "",
    };
  },
  watch: {
    objectdata: {
      handler(val, oldVal) {
        if (val != oldVal) {
          this.formObj = Object.assign({}, val);
          this.statusSwitch = val.status == "active" ? true : false;
          this.categories = this.formObj.categories
            ? this.formObj.categories.map((res) => {
                return res.id;
              })
            : "";
          this.editorData = this.formObj.content ? this.formObj.content : "";
          if (this.formObj.images && this.formObj.images.length > 0) {
            this.selectedImage = this.formObj.images[0];
          }
          if (this.formObj.events) {
            this.eventDate = this.formObj.events;
          }
          if (
            this.formObj.poll_choices &&
            this.formObj.poll_choices.length > 0
          ) {
            this.newChoices = this.formObj.poll_choices;
          }
          this.choiceTextBox = this.formObj.poll_textbox_enabled;
        }
        this.loading = false;
      },
      deep: true,
    },
  },
  methods: {
    changeResponse(v) {
      console.log("changeResponse", v);
      this.editorContent = v;
    },
    imageResponse: function (v) {
      this.selectedImage = v;
    },
    handleCommentStatus: function (comment, status) {
      let dataForm = { comment: comment, status: status };
      axios
        .post("/d/admin/comment/update-status", dataForm)
        .then((response) => {
          this.sbOptions = {
            status: true,
            type: "success",
            text: "Data has been updated!",
          };

          this.$emit("saved", true);
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
    deleteChoice: function (index) {
      this.newChoices.splice(index, 1);
    },
    clearChoice: function (index) {
      this.newChoices[index].content = "";
    },
    addNewChoice: function () {
      this.newChoices.push({});
    },
    newPost: function () {
      this.$router.push({ name: this.newRedirect });
    },

    uploader(editor) {
      editor.plugins.get("FileRepository").createUploadAdapter = (loader) => {
        return new UploadAdapter(loader);
      };
    },

    fetchCategories: async function () {
      await axios
        .get("/d/admin/fetch/non-paginate/categories")
        .then((response) => {
          this.categoryList = response.data;
          console.log(this.categoryList);
        });
    },

    submit: function () {
      this.loading = true;
      let images = this.selectedImage.hasOwnProperty("id")
        ? this.selectedImage.id
        : null;

      //   this.formObj.content = this.editorData;
      this.formObj.content = this.editorContent;
      this.formObj.type = this.typePost;
      // Set status value
      this.formObj.status = this.statusSwitch == true ? "active" : "draft";
      let eventOnly = null;
      let pollOnly = null;
      if (this.posttype == "event" || this.posttype == "poll") {
        eventOnly = this.eventDate;
      }

      if (this.posttype == "poll") {
        pollOnly = this.newChoices;
        this.formObj.poll_textbox_enabled = this.choiceTextBox;
      }

      let dataForm = {
        data: this.formObj,
        image: images,
        categories: this.categories,
        daterange: eventOnly,
        choices: pollOnly,
      };

      if (this.formObj.id) {
        let postID = this.formObj.id;
        let bdata = this.formObj;
        delete bdata["created_at"];
        delete bdata["updated_at"];
        delete bdata["images"];
        delete bdata["user_id"];
        delete bdata["events"];
        delete bdata["poll_choices"];
        delete bdata["poll_answer"];
        delete bdata["poll_answer_count"];
        delete bdata["comments"];
        delete bdata["categories"];
        dataForm = {
          data: bdata,
          image: images,
          id: postID,
          daterange: eventOnly,
          categories: this.categories,
          choices: pollOnly,
        };
      }

      // Send data to save
      axios
        .post(this.URLadd, dataForm)
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
              this.$refs.user_form_observer.reset();
              this.$router.push({
                name: this.editRedirect,
                params: { id: response.data.result.id },
              });
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
          .get(this.URLdelete + this.formObj.id)
          .then((response) => {
            this.sbOptions = {
              status: true,
              type: "success",
              text: "Data has been deleted",
            };
            setTimeout(() => {
              this.$router.push({ name: this.deleteRedirect });
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
  created() {
    this.fetchCategories();
  },
};
</script>
