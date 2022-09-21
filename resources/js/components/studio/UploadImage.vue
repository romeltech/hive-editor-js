<template>
  <div>
    <v-card>
      <v-card-text>
        <small>Featured Image</small>
        <v-img
          style="border-radius: 4px"
          :lazy-src="`${$baseUrl + '/images/placeholder-image.png'}`"
          max-height="200"
          width="200"
          :aspect-ratio="1"
          :src="`${
            imageSelected && imageSelected.hasOwnProperty('id')
              ? $baseUrl + '/file/' + imageSelected.path
              : $baseUrl + '/images/placeholder-image.png'
          }`"
        >
          <template v-slot:placeholder>
            <v-row class="fill-height ma-0" align="center" justify="center">
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
              imageSelected && imageSelected.hasOwnProperty('id') ? true : false
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
              imageSelected && imageSelected.hasOwnProperty("id")
                ? "Replace"
                : "Upload"
            }}</v-btn
          >
        </div>
      </v-card-text>
    </v-card>

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
 
import Studio from "./Studio"; 

export default {
    name: "Images",
  components: {
    Studio,
    
  },
  props:{
    selectedImage: { type: Object,
                default: null
    }, 
  },

  data() {
    return { 
      imageSelected: {},
       studioSettings: {
        dialog: false,
        multiSelect: false,
      },
    };
  },
  watch: {
    selectedImage: {
       handler(val, oldVal) {
        console.log(val);
        this.imageSelected = val;
       },
       deep: true,
  },
  },
  methods: {
      studioResponse: function (v) {
      this.studioSettings.dialog = v.dialog;
      this.imageSelected =
        v.images != null ? Object.assign({}, v.images[0]) : {};
          this.$emit("selected", this.imageSelected);
    }, 
    
    removeImage: function () {
      this.imageSelected = [];
      this.$emit("selected", {});
    },
    addImage: function () {
      this.studioSettings.dialog = true;
    },
  },
};
</script> 