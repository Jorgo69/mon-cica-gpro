import { Editor } from '@tiptap/core'
import StarterKit from '@tiptap/starter-kit'
import Placeholder from '@tiptap/extension-placeholder'
import Underline from '@tiptap/extension-underline'

export default function richEditor({ uniqueId, content = '', placeholder = 'Écrivez ici...' }) {
    // L'éditeur est stocké dans la closure, PAS dans le data Alpine.
    // Si on le met dans this.editor, Alpine le proxifie → ProseMirror crashe ("mismatched transaction").
    let _editor = null

    return {
        updatedAt: Date.now(),

        init() {
            _editor = new Editor({
                element: this.$refs.editorContent,
                extensions: [
                    StarterKit.configure({ underline: false }),
                    Placeholder.configure({ placeholder }),
                    Underline,
                ],
                content,
                editorProps: {
                    attributes: {
                        class: 'rich-editor-content min-h-[inherit] px-4 py-3 focus:outline-none text-sm',
                    },
                },
                onUpdate: ({ editor }) => {
                    this.updatedAt = Date.now()
                    const textarea = document.getElementById(uniqueId)
                    if (textarea) {
                        textarea.value = editor.getHTML()
                        textarea.dispatchEvent(new Event('input', { bubbles: true }))
                    }
                },
                onSelectionUpdate: () => {
                    this.updatedAt = Date.now()
                },
            })
        },

        destroy() {
            _editor?.destroy()
            _editor = null
        },

        // Toolbar helpers — @mousedown.prevent garde le focus, pas besoin de .focus() dans les chains
        toggleBold()        { _editor?.chain().toggleBold().run() },
        toggleItalic()      { _editor?.chain().toggleItalic().run() },
        toggleUnderline()   { _editor?.chain().toggleUnderline().run() },
        toggleStrike()      { _editor?.chain().toggleStrike().run() },
        toggleBulletList()  { _editor?.chain().toggleBulletList().run() },
        toggleOrderedList() { _editor?.chain().toggleOrderedList().run() },
        toggleBlockquote()  { _editor?.chain().toggleBlockquote().run() },
        setHeading(level)   { _editor?.chain().toggleHeading({ level }).run() },
        clearFormatting()   { _editor?.chain().clearNodes().unsetAllMarks().run() },
        isActive(type, opts) { return _editor?.isActive(type, opts ?? {}) ?? false },
    }
}
