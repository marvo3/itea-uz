(function(b) {
    b.widget("ui.tagit", {
        options: { allowDuplicates: !1, caseSensitive: !0, fieldName: "tags", placeholderText: null, readOnly: !1, removeConfirmation: !1, tagLimit: null, availableTags: [], autocomplete: {}, showAutocompleteOnFocus: !1, allowSpaces: !1, singleField: !1, singleFieldDelimiter: "," /*","*/, singleFieldNode: null, animate: !0, tabIndex: null, beforeTagAdded: null, afterTagAdded: null, beforeTagRemoved: null, afterTagRemoved: null, onTagClicked: null, onTagLimitExceeded: null, onTagAdded: null, onTagRemoved: null, tagSource: null },
        _create: function() {
            var a =
                this;
            this.element.is("input") ? (this.tagList = b("<ul></ul>").insertAfter(this.element), this.options.singleField = !0, this.options.singleFieldNode = this.element, this.element.addClass("tagit-hidden-field")) : this.tagList = this.element.find("ul, ol").andSelf().last();
            this.tagInput = b('<input type="text" data-inputmask-regex="[^,]*" />').addClass("ui-widget-content");
            this.options.readOnly && this.tagInput.attr("disabled", "disabled");
            this.options.tabIndex && this.tagInput.attr("tabindex", this.options.tabIndex);
            this.options.placeholderText && this.tagInput.attr("placeholder",
                this.options.placeholderText);
            this.options.autocomplete.source || (this.options.autocomplete.source = function(a, e) {
                var d = a.term.toLowerCase(),
                    c = b.grep(this.options.availableTags, function(a) {
                        return 0 === a.toLowerCase().indexOf(d) });
                this.options.allowDuplicates || (c = this._subtractArray(c, this.assignedTags()));
                e(c) });
            this.options.showAutocompleteOnFocus && (this.tagInput.focus(function(b, d) { a._showAutocomplete() }), "undefined" === typeof this.options.autocomplete.minLength && (this.options.autocomplete.minLength =
                0));
            b.isFunction(this.options.autocomplete.source) && (this.options.autocomplete.source = b.proxy(this.options.autocomplete.source, this));
            b.isFunction(this.options.tagSource) && (this.options.tagSource = b.proxy(this.options.tagSource, this));
            this.tagList.addClass("tagit").addClass("ui-widget ui-widget-content ui-corner-all").append(b('<li class="tagit-new"></li>').append(this.tagInput)).click(function(d) {
                var c = b(d.target);
                c.hasClass("tagit-label") ? (c = c.closest(".tagit-choice"), c.hasClass("removed") || a._trigger("onTagClicked",
                    d, { tag: c, tagLabel: a.tagLabel(c) })) : a.tagInput.focus()
            });
            var c = !1;
            if (this.options.singleField)
                if (this.options.singleFieldNode) {
                    var d = b(this.options.singleFieldNode),
                        f = d.val().split(this.options.singleFieldDelimiter);
                    d.val("");
                    b.each(f, function(b, d) { a.createTag(d, null, !0);
                        c = !0 }) } else this.options.singleFieldNode = b('<input type="hidden" style="display:none;" value="" name="' + this.options.fieldName + '" />'), this.tagList.after(this.options.singleFieldNode);
            c || this.tagList.children("li").each(function() {
                b(this).hasClass("tagit-new") ||
                    (a.createTag(b(this).text(), b(this).attr("class"), !0), b(this).remove())
            });
            this.tagInput.keydown(function(c) {
                if (c.which == b.ui.keyCode.BACKSPACE && "" === a.tagInput.val()) {
                    var d = a._lastTag();!a.options.removeConfirmation || d.hasClass("remove") ? a.removeTag(d) : a.options.removeConfirmation && d.addClass("remove ui-state-highlight") } else a.options.removeConfirmation && a._lastTag().removeClass("remove ui-state-highlight");
                if (/***c.which === b.ui.keyCode.COMMA && !1 === c.shiftKey || ***/ c.which === b.ui.keyCode.ENTER || c.which ==
                    b.ui.keyCode.TAB && "" !== a.tagInput.val() || c.which == b.ui.keyCode.SPACE && !0 !== a.options.allowSpaces && ('"' != b.trim(a.tagInput.val()).replace(/^s*/, "").charAt(0) || '"' == b.trim(a.tagInput.val()).charAt(0) && '"' == b.trim(a.tagInput.val()).charAt(b.trim(a.tagInput.val()).length - 1) && 0 !== b.trim(a.tagInput.val()).length - 1)) c.which === b.ui.keyCode.ENTER && "" === a.tagInput.val() || c.preventDefault(), a.options.autocomplete.autoFocus && a.tagInput.data("autocomplete-open") || (a.tagInput.autocomplete("close"), a.createTag(a._cleanedInput()))
            }).blur(function(b) {
                a.tagInput.data("autocomplete-open") ||
                    a.createTag(a._cleanedInput())
            });
            if (this.options.availableTags || this.options.tagSource || this.options.autocomplete.source) d = { select: function(b, c) { a.createTag(c.item.value);
                    return !1 } }, b.extend(d, this.options.autocomplete), d.source = this.options.tagSource || d.source, this.tagInput.autocomplete(d).bind("autocompleteopen.tagit", function(b, c) { a.tagInput.data("autocomplete-open", !0) }).bind("autocompleteclose.tagit", function(b, c) { a.tagInput.data("autocomplete-open", !1) }), this.tagInput.autocomplete("widget").addClass("tagit-autocomplete")
        },
        destroy: function() {
            b.Widget.prototype.destroy.call(this);
            this.element.unbind(".tagit");
            this.tagList.unbind(".tagit");
            this.tagInput.removeData("autocomplete-open");
            this.tagList.removeClass("tagit ui-widget ui-widget-content ui-corner-all tagit-hidden-field");
            this.element.is("input") ? (this.element.removeClass("tagit-hidden-field"), this.tagList.remove()) : (this.element.children("li").each(function() {
                b(this).hasClass("tagit-new") ? b(this).remove() : (b(this).removeClass("tagit-choice ui-widget-content ui-state-default ui-state-highlight ui-corner-all remove tagit-choice-editable tagit-choice-read-only"),
                    b(this).text(b(this).children(".tagit-label").text()))
            }), this.singleFieldNode && this.singleFieldNode.remove());
            return this
        },
        _cleanedInput: function() {
            return b.trim(this.tagInput.val().replace(/^"(.*)"$/, "$1")) },
        _lastTag: function() {
            return this.tagList.find(".tagit-choice:last:not(.removed)") },
        _tags: function() {
            return this.tagList.find(".tagit-choice:not(.removed)") },
        assignedTags: function() {
            var a = this,
                c = [];
            this.options.singleField ? (c = b(this.options.singleFieldNode).val().split(this.options.singleFieldDelimiter),
                "" === c[0] && (c = [])) : this._tags().each(function() { c.push(a.tagLabel(this)) });
            return c
        },
        _updateSingleTagsField: function(a) { b(this.options.singleFieldNode).val(a.join(this.options.singleFieldDelimiter)).trigger("change") },
        _subtractArray: function(a, c) {
            for (var d = [], f = 0; f < a.length; f++) - 1 == b.inArray(a[f], c) && d.push(a[f]);
            return d },
        tagLabel: function(a) {
            return this.options.singleField ? b(a).find(".tagit-label:first").text() : b(a).find("input:first").val() },
        _showAutocomplete: function() {
            this.tagInput.autocomplete("search",
                "")
        },
        _findTagByLabel: function(a) {
            var c = this,
                d = null;
            this._tags().each(function(f) {
                if (c._formatStr(a) == c._formatStr(c.tagLabel(this))) return d = b(this), !1 });
            return d },
        _isNew: function(a) {
            return !this._findTagByLabel(a) },
        _formatStr: function(a) {
            return this.options.caseSensitive ? a : b.trim(a.toLowerCase()) },
        _effectExists: function(a) {
            return Boolean(b.effects && (b.effects[a] || b.effects.effect && b.effects.effect[a])) },
        createTag: function(a, c, d) {
            var f = this;
            a = b.trim(a);
            this.options.preprocessTag && (a = this.options.preprocessTag(a));
            if ("" === a) return !1;
            if (!this.options.allowDuplicates && !this._isNew(a)) return a = this._findTagByLabel(a), !1 !== this._trigger("onTagExists", null, { existingTag: a, duringInitialization: d }) && this._effectExists("highlight") && a.effect("highlight"), !1;
            if (this.options.tagLimit && this._tags().length >= this.options.tagLimit) return this._trigger("onTagLimitExceeded", null, { duringInitialization: d }), !1;
            var g = b(this.options.onTagClicked ? '<a class="tagit-label"></a>' : '<span class="tagit-label"></span>').text(a),
                e = b("<li></li>").addClass("tagit-choice ui-widget-content ui-state-default ui-corner-all").addClass(c).append(g);
            this.options.readOnly ? e.addClass("tagit-choice-read-only") : (e.addClass("tagit-choice-editable"), c = b("<span></span>").addClass("ui-icon ui-icon-close"), c = b('<a><span class="text-icon">\u00d7</span></a>').addClass("tagit-close").append(c).click(function(a) { f.removeTag(e) }), e.append(c));
            this.options.singleField || (g = g.html(), e.append('<input type="hidden" value="' + g + '" name="' + this.options.fieldName + '" class="tagit-hidden-field" />'));
            !1 !== this._trigger("beforeTagAdded", null, {
                tag: e,
                tagLabel: this.tagLabel(e),
                duringInitialization: d
            }) && (this.options.singleField && (g = this.assignedTags(), g.push(a), this._updateSingleTagsField(g)), this._trigger("onTagAdded", null, e), this.tagInput.val(""), this.tagInput.parent().before(e), this._trigger("afterTagAdded", null, { tag: e, tagLabel: this.tagLabel(e), duringInitialization: d }), this.options.showAutocompleteOnFocus && !d && setTimeout(function() { f._showAutocomplete() }, 0))
        },
        removeTag: function(a, c) {
            c = "undefined" === typeof c ? this.options.animate : c;
            a = b(a);
            this._trigger("onTagRemoved",
                null, a);
            if (!1 !== this._trigger("beforeTagRemoved", null, { tag: a, tagLabel: this.tagLabel(a) })) {
                if (this.options.singleField) {
                    var d = this.assignedTags(),
                        f = this.tagLabel(a),
                        d = b.grep(d, function(a) {
                            return a != f });
                    this._updateSingleTagsField(d) }
                if (c) { a.addClass("removed");
                    var d = this._effectExists("blind") ? ["blind", { direction: "horizontal" }, "fast"] : ["fast"],
                        g = this;
                    d.push(function() { a.remove();
                        g._trigger("afterTagRemoved", null, { tag: a, tagLabel: g.tagLabel(a) }) });
                    a.fadeOut("fast").hide.apply(a, d).dequeue() } else a.remove(),
                    this._trigger("afterTagRemoved", null, { tag: a, tagLabel: this.tagLabel(a) })
            }
        },
        removeTagByLabel: function(a, b) {
            var d = this._findTagByLabel(a);
            if (!d) throw "No such tag exists with the name '" + a + "'";
            this.removeTag(d, b) },
        removeAll: function() {
            var a = this;
            this._tags().each(function(b, d) { a.removeTag(d, !1) }) }
    })
})(jQuery);
