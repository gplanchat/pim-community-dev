'use strict';

define([
        'jquery',
        'module',
        'oro/mediator',
        'routing'
    ], function (
        $,
        module,
        mediator,
        Routing
    ) {
        return {
            /**
             * Save an entity
             *
             * @param {String} code
             * @param {Object} data
             *
             * @return {Promise}
             */
            save: function (code, data) {
                return $.ajax({
                    type: 'POST',
                    url: this.getUrl(code),
                    data: JSON.stringify(data)
                }).then(function (entity) {
                    mediator.trigger('pim_enrich:form:entity:post_save', entity);

                    return entity;
                }.bind(this));
            },

            /**
             * Get the entity url
             * @param {String} code
             *
             * @return {String}
             */
            getUrl: function (code) {
                return Routing.generate(module.config().url, {code: code});
            }
        };
    }
);
