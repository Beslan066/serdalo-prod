<template>
    <div>
        <label v-if="label" class="mb-2 block" :for="id">{{ label }}:</label>
        <div class="mb-4 border rounded border-gray-400 editor-block">
            <div v-if="editor" class="c-editor-toolbar flex flex-wrap gap-1 p-2 pb-4 border-b border-gray-400">
                <button type="button" @click="editor.chain().focus().toggleBold().run()" :disabled="!editable || !editor.can().chain().focus().toggleBold().run()" :class="{ 'is-active': editor.isActive('bold') }">
                    <i class="ri-bold"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleItalic().run()" :disabled="!editable || !editor.can().chain().focus().toggleItalic().run()" :class="{ 'is-active': editor.isActive('italic') }">
                    <i class="ri-italic"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleStrike().run()" :disabled="!editable || !editor.can().chain().focus().toggleStrike().run()" :class="{ 'is-active': editor.isActive('strike') }">
                    <i class="ri-strikethrough"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleCode().run()" :disabled="!editable || !editor.can().chain().focus().toggleCode().run()" :class="{ 'is-active': editor.isActive('code') }">
                    <i class="ri-code-line"></i>
                </button>
                <button type="button" @click="updateLink('')">
  		    <i class="ri-link"></i>
		</button>
                <button type="button" @click="editor.chain().focus().unsetAllMarks().run()" :disabled="!editable">
                    clear marks
                </button>
                <button type="button" @click="editor.chain().focus().clearNodes().run()" :disabled="!editable">
                    <i class="ri-format-clear"></i>
                </button>
                <button type="button" @click="editor.chain().focus().setParagraph().run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('paragraph') }">
                    <i class="ri-paragraph"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleHeading({ level: 1 }).run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('heading', { level: 1 }) }">
                    <i class="ri-h-1"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleHeading({ level: 2 }).run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('heading', { level: 2 }) }">
                    <i class="ri-h-2"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleHeading({ level: 3 }).run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('heading', { level: 3 }) }">
                    <i class="ri-h-3"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleHeading({ level: 4 }).run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('heading', { level: 4 }) }">
                    <i class="ri-h-4"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleHeading({ level: 5 }).run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('heading', { level: 5 }) }">
                    <i class="ri-h-5"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleHeading({ level: 6 }).run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('heading', { level: 6 }) }">
                    <i class="ri-h-6"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleBulletList().run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('bulletList') }">
                    <i class="ri-list-unordered"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleOrderedList().run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('orderedList') }">
                    <i class="ri-list-ordered"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleCodeBlock().run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('codeBlock') }">
                    <i class="ri-code-box-line"></i>
                </button>
                <button type="button" @click="editor.chain().focus().toggleBlockquote().run()" :disabled="!editable" :class="{ 'is-active': editor.isActive('blockquote') }">
                    <i class="ri-double-quotes-l"></i>
                </button>
                <button type="button" @click="editor.chain().focus().setHorizontalRule().run()" :disabled="!editable">
                    <i class="ri-separator"></i>
                </button>
                <button type="button" @click="editor.chain().focus().setHardBreak().run()" :disabled="!editable">
                    <i class="ri-text-wrap"></i>
                </button>
                <button type="button" @click="addImage" :disabled="!editable">
                    <i class="ri-image-line"></i>
                </button>
                <button type="button" @click="editor.chain().focus().undo().run()" :disabled="!editable || !editor.can().chain().focus().undo().run()">
                    <i class="ri-arrow-go-back-line"></i>
                </button>
                <button type="button" @click="editor.chain().focus().redo().run()" :disabled="!editable || !editor.can().chain().focus().redo().run()">
                    <i class="ri-arrow-go-forward-line"></i>
                </button>
            </div>
            <editor-content :editor="editor" />
        </div>
        <div v-if="error" class="text-red-400">{{ displayError }}</div>
    </div>
</template>

<script>
    import { eventBus } from '@/app'

    import { Editor, EditorContent } from '@tiptap/vue-2'
    import StarterKit from '@tiptap/starter-kit'
    import Document from '@tiptap/extension-document'
    import Paragraph from '@tiptap/extension-paragraph'
    import Text from '@tiptap/extension-text'
    import Dropcursor from '@tiptap/extension-dropcursor'
    import Image from '@tiptap/extension-image'
    import Link from '@tiptap/extension-link'


    export default {
        components: {
            EditorContent,
        },
        props: {
            id: {
                type: String,
            },
            value: {
                type: String,
                default: '',
            },
            editable: {
                type: Boolean,
                default: true,
            },
            label: String,
            error: [String, Array],
        },
        data() {
            return {
                editor: null,
            }
        },
        watch: {
            value(value) {
                const isSame = this.editor.getHTML() === value
                if (isSame) {
                    return
                }
                this.editor.commands.setContent(value, false)
            },
        },
        computed: {
            displayError() {
                return (Array.isArray(this.error)) ? this.error.join('; ') : this.error
            }
        },
        methods: {
            addImage() {
                eventBus.$emit('openFilesModal', 'Tiptap')
            },updateLink(href) {
    const content = this.editor.getHTML();
    const linkIndex = content.indexOf('<a');
    if (linkIndex !== -1) {
        const closingIndex = content.indexOf('>', linkIndex) + 1;
        const newContent = content.slice(0, closingIndex) + href + content.slice(closingIndex);
        this.editor.commands.setContent(newContent, false);
    } else {
        const wrappedContent = `<a href="${href}">${content}</a>`;
        this.editor.commands.setContent(wrappedContent, false);
    }
}

        },
        mounted() {
            eventBus.$on('selectedFilesTiptap', (files) => {
                this.editor.chain().focus().setImage({ src: files[0].storage_path }).run()
            })
            this.editor = new Editor({
                content: this.value,
                extensions: [
                    StarterKit,
                    Text,
                    Image,
                    Dropcursor,
                    Link,

                ],
                 editable: this.editable,
                     injectCSS: false,
                onUpdate: () => {
                    this.$emit('input', this.editor.getHTML())
                },
                //autofocus: true,
                editable: this.editable,
                injectCSS: false,
                onUpdate: () => {
                    this.$emit('input', this.editor.getHTML())
                },
            })
        },
        beforeUnmount() {
            this.editor.destroy()
        },
    }
</script>
<style lang="scss">

.editor-block {
    height: auto;
}
.c-editor-toolbar {
    button {
        margin: 0.15rem;
        margin-right: 0.25rem;
        padding: 0.15rem 0.6rem;
        border: 1px solid gray;
        border-radius: 0.25rem;
        color: #000;
        &.is-active, &:hover {
            background: #000;
            color: #fff;
            border-color: #000;
        }
        &:disabled {
            background: gray;
            color: #000;
            border-color: gray;
            cursor: default;
        }
        i {
            font-size: 1.25rem;
            line-height: 1.75rem;
        }
    }
}
.ProseMirror {
    padding: 0.65rem;
    margin-bottom: 20px;
    outline: none;
    min-height: 20rem;
    > * + * {
        margin-top: 0.75em;
    }
    ul,
    ol {
        padding: 0 1rem;
    }

    h1 {
        font-size: 2.25rem;
        line-height: 2.5rem;
    }
    h2 {
        font-size: 1.875rem;
        line-height: 2.25rem;
    }
    h3 {
        font-size: 1.5rem;
        line-height: 2rem;
    }
    h4 {
        font-size: 1.25rem;
        line-height: 1.75rem;
    }
    h5 {
        font-size: 1.125rem;
        line-height: 1.75rem;
    }
    h6 {
        font-size: 0.875rem;
        line-height: 1.25rem;
    }
    code {
        background-color: rgba(#616161, 0.1);
        color: #616161;
    }
    pre {
        background: #0D0D0D;
        color: #FFF;
        font-family: 'JetBrainsMono', monospace;
        padding: 0.75rem 1rem;
        border-radius: 0.5rem;
        code {
            color: inherit;
            padding: 0;
            background: none;
            font-size: 0.8rem;
        }
    }
    img {
        max-width: 100%;
        height: auto;
    }
    blockquote {
        padding-left: 1rem;
        border-left: 2px solid rgba(#0D0D0D, 0.1);
    }
    hr {
        border: none;
        border-top: 2px solid rgba(#0D0D0D, 0.1);
        margin: 2rem 0;
    }
    img {
        max-width: 100%;
        height: auto;

        &.ProseMirror-selectednode {
            outline: 3px solid #68CEF8;
        }
    }
}
</style>
