import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";
import { TextControl, TextareaControl } from "@wordpress/components";
import EditorBlockShell from "../../_shared/EditorBlockShell";

export default function Edit({ attributes, setAttributes, clientId }) {
  const { eyebrow, headline } = attributes;
  const blockProps = useBlockProps({
    className: "container cb-hts-js-2026-editor-block",
  });

  return (
    <EditorBlockShell blockProps={blockProps} clientId={clientId} title="CB Products Nav" textDomain="cb-hts-js-2026">
      <TextControl
        label={__("Eyebrow", "cb-hts-js-2026")}
        value={eyebrow}
        onChange={(value) => setAttributes({ eyebrow: value })}
      />
      <TextareaControl
        label={__("Headline", "cb-hts-js-2026")}
        value={headline}
        onChange={(value) => setAttributes({ headline: value })}
        help={__("Wrap highlighted text in <span>.", "cb-hts-js-2026")}
      />
    </EditorBlockShell>
  );
}
