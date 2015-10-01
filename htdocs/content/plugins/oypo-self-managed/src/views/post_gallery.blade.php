<style type='text/css'>
    #{{$selector}} {
        margin: auto;
    }
    #{{$selector}} .gallery-item {
        float: {{$float}};
        margin-top: 10px;
        text-align: center;
        width: {{$itemwidth}};
    }
    #{{$selector}} img {
        border: 2px solid #cfcfcf;
    }
    #{{$selector}} .gallery-caption {
        margin-left: 0;
    }
</style>
<!-- see gallery_shortcode() in wp-includes/media.php -->
<div id='{{$selector}}' class='gallery galleryid-{{$id}}'>

    @foreach($attachments as $attachment)

        <{{$itemtag}} class='gallery-item'>
            <{{$icontag}} class='gallery-icon'>
                {!!$attachment['link']!!}
            </{{$icontag}}>
            <{{$captiontag}} class='gallery-caption'>

                {!!$attachment['postExcerpt']!!}

<script language="JavaScript" src="http://www.oypo.nl/pixxer/api/orderbutton.asp?
		foto={!!$attachment['thumbnailPhoto']!!}&
		thumb={!!$attachment['thumbnailPhoto']!!}&
		profielid={!!$profielId!!}&
        buttonadd=http://www.kijk.us/selectionadd.gif&
        buttondel=http://www.kijk.us/selectiondel.gif"></script>

            </{{$captiontag}}>

        </{{$itemtag}}>

        @if($attachment['showBr'])
            <br style='clear: both;' />
        @endif

    @endforeach

</div>