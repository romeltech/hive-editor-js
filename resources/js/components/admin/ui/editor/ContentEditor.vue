<template>
  <div>
    <div class="text-right mb-3">
      <v-btn class="" @click="addMedia">Add Media</v-btn>
    </div>

    <div class="editorx_body mb-3 py-5">
      <!--editorjs id-->
      <div class id="codex-editor" />
    </div>
    <v-btn class="primary" @click="save()">save</v-btn>
    <div class="editorx_body mt-3">
      <pre>{{ value }}</pre>
    </div>
  </div>
</template>

<script>
import EditorJS from "@editorjs/editorjs";
import Header from "editorjs-header-with-alignment";
import Underline from "@editorjs/underline";
import Paragraph from "editorjs-paragraph-with-alignment";
import Marker from "@editorjs/marker";
import AlignmentTuneTool from "editorjs-text-alignment-blocktune";
import Quote from "@editorjs/quote";
import NestedList from "@editorjs/nested-list";
import Table from "@editorjs/table";
import RawTool from "@editorjs/raw";
import CodeTool from "@editorjs/code";
import Alert from "editorjs-alert";
import ToggleBlock from "editorjs-toggle-block";
import Embed from "@editorjs/embed";
import ChangeCase from "editorjs-change-case";
import SimpleImage from "@editorjs/simple-image";
import ImageTool from "@editorjs/image";
import VideoTool from "@weekwood/editorjs-video";

// import AttachesTool from "@editorjs/attaches";

export default {
  data() {
    return {
      value: {
        blocks: [],
      },
      tempValue: [
        {
          type: "image",
          data: {
            url: "https://aboudcrm.com/autohub/wp-content/uploads/2022/08/MicrosoftTeams-image-90.jpg",
            caption: "",
            withBorder: false,
            withBackground: false,
            stretched: false,
          },
          // id: "anD7JsZ0qC",
        },
      ],
    };
  },

  methods: {
    // editor.blocks.insert(
    // "image",
    // {
    //     file: {
    //     url : "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg"
    //     },
    //     caption : "Roadster // tesla.com",
    //     withBorder : false,
    //     withBackground : false,
    //     stretched : true
    // })

    addMedia() {
      //   let toInsert = this.value.blocks.push(this.tempValue);
      //   editor.blocks.insert("image", {
      //     url: "https://aboudcrm.com/autohub/wp-content/uploads/2022/08/MicrosoftTeams-image-90.jpg",
      //     caption: "Added from Media",
      //     withBorder: false,
      //     withBackground: false,
      //     stretched: false,
      //   });

      // attaches
      editor.blocks.insert("video", {
        file: {
          url: "https://www.tesla.com/tesla_theme/assets/img/_vehicle_redesign/roadster_and_semi/roadster/hero.jpg",
        },
        caption: "Roadster // tesla.com",
        withBorder: false,
        withBackground: false,
        stretched: true,
      });
    },
    save() {
      editor.save().then((savedData) => {
        this.value = savedData;
        console.log("this.value", this.value);
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
          //   image: {
          //     class: ImageTool,
          //     config: {
          //       endpoints: {
          //         byFile: "http://localhost:8000/d", // Your backend file uploader endpoint
          //         byUrl: "http://localhost:8000/d", // Your endpoint that provides uploading by Url
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
          toggle: {
            class: ToggleBlock,
            inlineToolbar: true,
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
          video: {
            class: VideoTool,
            config: {
              endpoints: {
                byFile: "http://localhost:8008/uploadFile", // Your backend file uploader endpoint
                byUrl: "http://localhost:8008/fetchUrl", // Your endpoint that provides uploading by Url
              },
            },
          },
        },
        onReady: function () {
          //   console.log("ready");
        },
        // onChange: function () {
        //   console.log("change");
        //   this.save();
        // },
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
