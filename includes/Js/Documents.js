import Ajax from "./Ajax";

export default class Documents {


    static edit() {

    }

    static new() {
        $( '.js-add-document-modal' ).modal( { closable: false } ).modal( 'show' );
        $( "input[name=create_date]" ).closest( ".ui.calendar" ).calendar( {
            type: "date",
            dateFormat: "yyyy-mm-dd"
        } );
    }

    static reloadDocuments() {

    }

    static submit() {
        Ajax.create()
            .url( Urls.AddDocument )
            .form( ".js-add-document-modal form" )
            .errors( ".js-add-document-modal .js-errors" )
            .done( function ( documentID ) {
                this.close();
                this.reloadDocuments();
            }.bind( this ))
            .run();
    }

    static close() {
        $( '.js-add-document-modal' ).modal( "hide" );
        $( '.js-add-document-modal form' ).get(0).reset();
        $( "input[name=create_date]" ).closest( ".ui.calendar" ).calendar( "clear" );
        $( ".js-add-document-modal .js-errors" ).hide();
    }

    static setup() {
        $( ".js-add-document" ).click( this.new );
        $( ".js-add-document-modal .js-submit" ).click( this.submit );
        $( ".js-add-document-modal .js-close" ).click( this.close );
    }
};