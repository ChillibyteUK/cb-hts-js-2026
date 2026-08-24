import { __ } from "@wordpress/i18n";
import { useBlockProps } from "@wordpress/block-editor";
import { TextControl, TextareaControl } from "@wordpress/components";

export default function Edit({ attributes, setAttributes }) {
  const { eyebrow, headline } = attributes;
  const blockProps = useBlockProps({
    className: "container cb-hts-js-2026-editor-block",
  });

  return (
    <div {...blockProps}>
      <p className="cb-hts-js-2026-editor-block__title">CB Products Nav</p>
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
    </div>
  );
}
