<template>
  <div>
    <div class="text-right mb-3">
      <v-btn class="" @click="addMedia">Add Media</v-btn>
    </div>

    <div class="editorx_body mb-3 py-5">
      <!--editorjs id-->
      <div class id="codex-editor" />
    </div>
    <!-- <div class="editorx_body mt-3">
      <pre>{{ value }}</pre>
    </div> -->
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
import EditorJS from "@editorjs/editorjs";
import Header from "editorjs-header-with-alignment";
import Underline from "@editorjs/underline";
import Paragraph from "editorjs-paragraph-with-alignment";
import Marker from "@editorjs/marker";
// import AlignmentTuneTool from "editorjs-text-alignment-blocktune";
import Quote from "@editorjs/quote";
import NestedList from "@editorjs/nested-list";
import Table from "@editorjs/table";
import RawTool from "@editorjs/raw";
import CodeTool from "@editorjs/code";
import Alert from "editorjs-alert";
import Embed from "@editorjs/embed";
import ChangeCase from "editorjs-change-case";
import SimpleImage from "@editorjs/simple-image";
// import ImageTool from "@editorjs/image";
// import VideoTool from "@weekwood/editorjs-video";
// import AttachesTool from "@editorjs/attaches";

import Studio from "../../../studio/Studio.vue";
export default {
  props: {
    propContent: {
      type: String,
      default: "",
    },
  },
  components: {
    Studio,
  },
  data() {
    return {
      studioSettings: {
        dialog: false,
        multiSelect: false,
      },
      selectedImage: {},

      value: {
        blocks: [],
      },
    };
  },
  watch: {
    propContent: {
      handler(val, oldVal) {
        if (val) {
          this.value = JSON.parse(val);
          editor.blocks.render(this.value);
        }
      },
      deep: true,
    },
  },
  methods: {
    studioResponse(v) {
      this.studioSettings.dialog = v.dialog;
      this.selectedImage =
        v.images != null ? Object.assign({}, v.images[0]) : {};
      let pathToInsert = this.$baseUrl + "/file/" + this.selectedImage.path; // "https://aboudcrm.com/autohub/wp-content/uploads/2022/08/MicrosoftTeams-image-90.jpg";

      let lastIndex = editor.blocks.getBlocksCount();
      // for image
      editor.blocks.insert(
        "image",
        {
          url: pathToInsert,
          caption: "",
          withBorder: false,
          withBackground: false,
          stretched: false,
        },
        {},
        lastIndex
      );
      // for video
      //   editor.blocks.insert("video", {
      //     file: {
      //       url: "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg",
      //     },
      //     caption: "Roadster // tesla.com",
      //     withBorder: false,
      //     withBackground: false,
      //     stretched: true,
      //   });
    },
    addMedia() {
      this.studioSettings.dialog = true;
    },
    save() {
      editor.save().then((savedData) => {
        this.value = savedData;
        // emit value
        this.$emit("changed", JSON.stringify(this.value));
      });
    },
    myEditor: function () {
      window.editor = new EditorJS({
        holder: "codex-editor",
        autofocus: true,
        /**
         * This Tool will be used as default
         */
        defaultBlock: "paragraph",
        tools: {
          header: {
            class: Header,
            config: {
              placeholder: "Enter a header",
              levels: [1, 2, 3, 4, 5, 6],
              defaultLevel: 3,
              defaultAlignment: "left",
            },
          },
          paragraph: {
            class: Paragraph,
            inlineToolbar: true,
          },
          underline: Underline,
          Marker: {
            class: Marker,
            shortcut: "CMD+SHIFT+M",
          },
          changeCase: {
            class: ChangeCase,
            config: {
              showLocaleOption: true, // enable locale case options
              locale: "tr", // or ['tr', 'TR', 'tr-TR']
            },
          },
          list: {
            class: NestedList,
          },
          image: SimpleImage,
          //   video: {
          //     class: VideoTool,
          //     config: {
          //       endpoints: {
          //         byFile: "http://localhost:8008/uploadFile", // Your backend file uploader endpoint
          //         byUrl: "http://localhost:8008/fetchUrl", // Your endpoint that provides uploading by Url
          //       },
          //     },
          //   },
          quote: Quote,
          table: {
            class: Table,
          },
          alert: {
            class: Alert,
            inlineToolbar: true,
            shortcut: "CMD+SHIFT+A",
            config: {
              defaultType: "primary",
              messagePlaceholder: "Enter something",
            },
          },
          embed: {
            class: Embed,
            inlineToolbar: true,
          },

          code: CodeTool,
          raw: RawTool,
          //   attaches: {
          //     class: AttachesTool,
          //     config: {
          //       endpoint: "http://localhost:8000/uploadFile",
          //     },
          //   },
        },
        onReady: function () {
          //   console.log("ready");
        },
        onChange: this.save,
      });
    },
  },
  mounted: function () {
    this.myEditor();
  },
};
</script>

<style lang="css" scoped >
.editorx_body {
  width: 100%;
  border: 0.5px solid #000;
  border-radius: 4px;
  box-sizing: border-box;
}
.ce-block--focused {
  background: linear-gradient(
    90deg,
    rgba(2, 0, 36, 1) 0%,
    rgba(9, 9, 121, 0.5438550420168067) 35%,
    rgba(0, 212, 255, 1) 100%
  );
}
</style>
