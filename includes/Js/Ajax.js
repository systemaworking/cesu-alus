class AjaxObject
{
    constructor() {
        this.options = {
            url: null,
            form: null,
            errors: null,
            done: null,
            fail: function ( errors ) {
                this.setErrorsText( errors.join( "<br>" ) )
            }.bind( this )
        };
    }

    url( url ) {
        this.options.url = url;
        return this;
    }

    errors( selector ) {
        this.options.errors = selector;
        return this;
    }

    form( selector ) {
        this.options.form = selector;
        return this;
    }

    done( callback ) {
        this.options.done = callback;
        return this;
    }

    fail( callback ) {
        this.options.fail = callback;
        return this;
    }

    setErrorsText( text ) {
        if ( this.options.errors ) {
            if ( text ) {
                $( this.options.errors ).html( text ).show();
            } else {
                $( this.options.errors ).html( "" ).hide();
            }
        }
    }

    run() {
        this.setErrorsText( "" );

        $.ajax({
            url: this.options.url,
            data: this.options.form ? new FormData( $( this.options.form ).get(0) ) : {},
            processData: false,
            contentType: false,
            type: 'POST'
        }).done( function ( json ) {
            if ( json.success ) {
                if ( this.options.done ) {
                    this.options.done( json.data );
                }
            } else {
                this.options.fail( $.isPlainObject( json ) ? json.errors : [ json ] );
            }
        }.bind( this ));

        return this;
    }
}

export default class Ajax {
    static create() {
        return new AjaxObject();
    }
};