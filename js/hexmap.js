var editor;
var contributor;
var toUpdate = [];
jQuery(document).ready(function($) {

    //$('.hex-top.add-hex').remove();
    //arrange_hexes();

    (function arrange_hexes() {
        $('.column:not(.add-column)').each(function() {

            let col = $(this).attr('data-column');
            let this_col = $(this);
            let target = $('.hex-top[data-col=' + col + ']');
            target.sort((a, b) => (parseInt(a.getAttribute('data-row')) < parseInt(b.getAttribute('data-row'))) ? 1 : -1);
            target.each(function() {
                let new_hex = $(this).clone();
                $(this).remove();
                this_col.prepend(new_hex);
            })

        });
        $('.the_map').show();
    })()
    $('div:not(.basic-information)>.hex-top:not(.hidden, .add-hex, .void)').on('click', load_hex);
    let draggin = false;
    $(".map-container").on('mousedown touchstart', function(e) {
        //e.preventDefault();
        if (e.which == 2) {
            $(".map-container").on('mouseup touchend ', function(e) {
                e.preventDefault();

                draggin = false;
                $("body").css("cursor", "");
                $(".map-container").unbind('mouseup touchend mouseout');
                $(window).unbind('mousemove touchmove');

                $(".inner-hexmap").removeClass('moving');

            });

            draggin = true;
            if (draggin) {
                $(".inner-hexmap").addClass('moving');
                let prevPosX = e.pageX;
                let prevPosY = e.pageY;
                $(window).on('mousemove touchmove', function() {

                    let e = window.event;
                    e.preventDefault();
                    let moveX = (prevPosX - e.pageX) * -1;
                    prevPosX = e.pageX;
                    let moveY = (prevPosY - e.pageY) * -1;
                    prevPosY = e.pageY;
                    let newPosX = ($('.the_map').position().left + moveX < 0) ? $('.the_map').position().left + moveX : 0;
                    let newPosY = ($('.the_map').position().top + moveY < 0) ? $('.the_map').position().top + moveY : 0;
                    newPosX = ((($('.inner-hexmap').innerWidth() - $(".map-container").innerWidth()) * -1) > newPosX) ? (($('.inner-hexmap').innerWidth() - $(".map-container").innerWidth()) * -1) : newPosX;
                    newPosY = ((($('.inner-hexmap').innerHeight() - $(".map-container").innerHeight()) * -1) > newPosY) ? (($('.inner-hexmap').innerHeight() - $(".map-container").innerHeight()) * -1) : newPosY;
                    $("body").css("cursor", "grabbing", "important");
                    $('.the_map').css("top", newPosY);
                    $('.the_map').css("left", newPosX);

                })
            }
        }

    });
    $('#create-new-item').on('click', function(e) {
        e.preventDefault();
        $('.create-item-warning').remove();
        if ($('#new-item-type').val() == "" || $('#new-item-name').val() == "") {
            $('#create-new-item').before("<span class='create-item-warning'>*Fill everything in</span>")
        } else {
            $.ajax({
                url: getHex.ajax_url,
                type: 'POST',
                data: {
                    action: 'create_item',
                    type: $('#new-item-type').val(),
                    name: $('#new-item-name').val()
                },
                beforeSend: function() {

                },
                success: function(html) {
                    $('#new-item-name').val("");
                    $('#new-item-type').val("")
                    $('.item-list').html(html);
                },
                fail: function() {

                    console.log("Ajax request failed")
                }
            });
            return false;
        }

    })
    $('#your-items').on('change', function(e) {
        e.preventDefault();

        if ($(this).val() == "0") {
            $(".item").show();
        } else {
            $(".item").show();
            $(".item:not([data-type='" + $(this).val() + "'])").hide();

        }
    })
    $(".fold-in").on('click ', function(e) {
        e.preventDefault();
        $(this).parent().toggleClass('closed');
        if ($(".closed:not(.creation-tools)").length > 0) {
            $('.creation-tools').removeClass('closed')

        } else {
            $('.creation-tools').addClass('closed')
        }
    })
    $(".instructions-button").on('click', function() {
        $(this).parent().toggleClass('closed');
        $('.instructions').removeClass('closed')
    })
    $(".tools-button").on('click', function() {
        $(this).parent().toggleClass('closed');
        $('.site-creation').removeClass('closed')
    })
    $('.map-toolbar>span').on('click', function(e) {
        $('.inner-hexmap').unbind('mousedown');
        $('.map-toolbar>span').removeClass('active');
        $('.map-container').removeClass('terrain-paint ');
        $(this).addClass("active")
        $('#terrain-tool-options').hide();
        if ($(this).attr('id') == "default-tool") {

            $('div:not(.basic-information)>.hex-top:not(.hidden, .add-hex, .void)').on('click', load_hex);
        } else {
            $('div:not(.basic-information)>.hex-top:not(.hidden, .add-hex, .void)').unbind('click');

        }
        if ($(this).attr('id') == "terrain-tool") {
            $('#terrain-tool-options').show();
            $('.map-container').addClass('terrain-paint');
            $('.inner-hexmap').on('mousedown', paint_hex);
            $('.inner-hexmap').on('mouseup', upload_terrain);
        }
        if ($(this).attr('id') == "hide-tool") {
            $('.map-container').addClass('show-paint');
            $('.inner-hexmap').on('mousedown', toggleHidden);
            $('.inner-hexmap').on('mouseup', uploadHidden);
        }
    })

    function load_hex(event) {
        event.preventDefault();
        var post_id = $(".map-container").attr("data-hexmap");
        var hex_col = $(this).attr("data-col");
        var hex_row = $(this).attr("data-row");
        var hex = $(this).find('.tile').attr('id');
        $.ajax({
            url: getHex.ajax_url,
            type: 'post',
            data: {
                action: 'load_hex_info',
                query_vars: getHex.query_vars,
                post_id: post_id,
                hex: hex
            },
            beforeSend: function() {
                $('#hex-info').html('');
                $('#hex-info').addClass('active');
                $('#hex-info').append('<div class="ajax-loader"></div>');
            },
            success: function(html) {
                $('.tools').css('left', -320);
                $('.ajax-loader').remove();

                $('#hex-info').append(html);
                $("#close-hex:not(.editor, .contributor)").click(function(event) {
                    event.preventDefault();
                    $('.hex-information').remove();
                    $('#hex-info').removeClass('active');
                });
                $('#add-note').on('click', function(e) {
                    if ($('#new-note-name').val() != "" && $('#new-note-content').val() != "") {
                        $.ajax({
                            url: getHex.ajax_url,
                            type: 'post',
                            data: {
                                action: 'add_note',
                                content: $('#new-note-content').val(),
                                title: $('#new-note-name').val(),

                            },
                            beforeSend: function() {
                                $('#add-note').css("pointer-events", "none")
                            },
                            success: function(html) {

                                $('#add-note').css("pointer-events", "")

                                if ($('#hex-notes>.note').length == 0) {
                                    $('#hex-notes').text('');
                                }
                                $('#hex-notes').append(html);
                                $('.hide-item').unbind();
                                $('.hide-item').on('click', function(e) {
                                    $(this).toggleClass('hidden');
                                })
                                $('#new-note-content').val('');
                                $('#new-note-name').val('');
                                $('.add, .new-item-container').removeClass('active');
                                $('.note .remove-item').on('click', function(e) {
                                    $(this).parent().parent().remove();
                                })

                            },
                            fail: function() {
                                console.log("Ajax request failed")
                            }
                        });
                    }
                })
                $('#close-hex.editor').on('click', function(e) {
                    $('#close-hex.editor').unbind('click');
                    let notes = [];
                    $('#hex-notes>.note').each(function() {
                        let hidden = $(this).find('.hide-item').hasClass('hidden') ? 1 : 0;
                        let note = {
                            title: $(this).find('.note-title').text(),
                            writer: $(this).find('.note-writer').attr('data-writer'),
                            content: $(this).find('.note-content').text(),
                            hidden: hidden
                        }
                        notes.push(note);
                    })

                    $.ajax({
                        url: getHex.ajax_url,
                        type: 'post',
                        data: {
                            action: 'update_hex',
                            description: $('#hex-description>div').html(),
                            post_id: $('.map-container').attr('data-hexmap'),
                            hex_id: $(this).attr('data-hex'),
                            notes: notes
                        },
                        beforeSend: function() {
                            $('#close-hex.editor').prev().remove();
                            $('#close-hex.editor').before("<span>Saving...</span>");
                        },
                        success: function(html) {
                            $('.tools').css('left', '');
                            $('.hex-information').remove();
                            $('#hex-info').removeClass('active');

                        },
                        fail: function() {
                            console.log("Ajax request failed")
                        }
                    });

                })

                $('.add').on('click', function(e) {
                    $(this).parent().next().toggleClass('active');
                })
                $('.hide-item').on('click', function(e) {
                    $(this).toggleClass('hidden');
                })
                $('.note .remove-item').on('click', function(e) {
                    $(this).parent().parent().remove();
                })
            },
            fail: function() {
                console.log("Ajax request failed")
            }
        });
        return false;
    }

    function upload_terrain(e) {

        if (e.button == 0) {
            $('.hex-top *').css('pointer-events', '');
            $('.hex-top').off('mouseover');
            $('.hex-top').attr('data-painted', '');
            $.ajax({
                url: getHex.ajax_url,
                type: 'post',
                data: {
                    action: 'update_terrain',
                    terrain: toUpdate,
                    post_id: $('.map-container').attr('data-hexmap')
                },
                beforeSend: function() {
                    $('.inner-hexmap').off('mousedown');
                    $('.inner-hexmap').off('mouseup');
                },
                success: function(html) {
                    $('.inner-hexmap').on('mousedown', paint_hex);
                    $('.inner-hexmap').on('mouseup', upload_terrain);

                },
                fail: function() {
                    console.log("Ajax request failed")
                }
            })
        }
    }

    function paint_hex(e) {
        if (e.button == 0) {
            e.preventDefault();
            toUpdate = [];
            $('.hex-top:not(.add-hex) *').css('pointer-events', 'none');
            $('.hex-top:not(.add-hex)').off('mouseover');
            $('.hex-top:not(.add-hex)').on('mouseover', function(e) {

                if ($(this).attr('data-painted') != 'true') {
                    $(this).attr('data-terrain', $('#terrains').val());
                    let terrain_info = {
                        terrain: $(this).attr('data-terrain'),
                        hex_id: $(this).find('.hex-container').attr('id')
                    }

                    toUpdate.push(terrain_info);


                    $(this).attr('data-painted', true);
                }


            })

        }
    }
    let firstShow = true;
    let hiddenValue;

    function toggleHidden(e) {
        if (e.button == 0) {
            e.preventDefault();
            toUpdate = [];

            $('.hex-top:not(.add-hex) *').css('pointer-events', 'none');
            $('.hex-top:not(.add-hex)').off('mouseover');
            $('.hex-top:not(.add-hex)').on('mouseover', function(e) {
                if (firstShow) {
                    firstShow = false;
                    hiddenValue = ($(this).attr('data-hidden') == '0') ? 1 : 0;
                }
                if ($(this).attr('data-toggled') != 'true') {

                    $(this).attr('data-hidden', hiddenValue);
                    let toggleInfo = {
                        hidden: hiddenValue,
                        hex_id: $(this).find('.hex-container').attr('id')
                    }

                    toUpdate.push(toggleInfo);


                    $(this).attr('data-toggled', true);
                }


            })

        }

    }

    function uploadHidden(e) {
        if (e.button == 0) {
            $('.hex-top *').css('pointer-events', '');
            $('.hex-top').off('mouseover');
            $('.hex-top').attr('data-toggled', '');
            $.ajax({
                url: getHex.ajax_url,
                type: 'post',
                data: {
                    action: 'update_hidden',
                    hidden: toUpdate,
                    post_id: $('.map-container').attr('data-hexmap')
                },
                beforeSend: function() {
                    $('.inner-hexmap').off('mousedown');
                    $('.inner-hexmap').off('mouseup');
                },
                success: function(html) {
                    console.log(html);
                    $('.inner-hexmap').on('mousedown', toggleHidden);
                    $('.inner-hexmap').on('mouseup', uploadHidden);

                },
                fail: function() {
                    console.log("Ajax request failed")
                }
            })
            firstShow = true;
        }
    }

    $('.add-hex-column:not(.add-hex-row)').on('click', add_column);
    $('.add-hex-row:not(.add-hex-column)').on('click', add_row);
    $('.add-hex-row.add-hex-column').on('click', add_row_column);

    function add_row_column(e) {
        let new_hexes = [];
        let total_col = parseInt($('.the_map').attr('data-total-col')) + 1;
        let total_row = parseInt($('.the_map').attr('data-total-row')) + 1;
        $('.add-hex-column').each(function(e) {
            new_hexes.push({
                'column_hex': $('.column:last-of-type').attr('data-column'),
                'row_hex': $(this).attr('data-row'),
                'terrain': 'plains',
            })
        })
        $('.add-hex-row:not(.add-hex-column)').each(function(e) {
            new_hexes.push({
                'column_hex': $(this).parent().attr('data-column'),
                'row_hex': $(this).attr('data-row'),
                'terrain': 'plains',
            })
        })
        add_hexes(new_hexes, total_col, total_row);
    }

    function add_column(e) {
        let new_hexes = [];
        let total_col = parseInt($('.the_map').attr('data-total-col')) + 1;
        let total_row = parseInt($('.the_map').attr('data-total-row'));

        $('.add-hex-column:not(.add-hex-row)').each(function(e) {
            new_hexes.push({
                'column_hex': $('.column:last-of-type').attr('data-column'),
                'row_hex': $(this).attr('data-row'),
                'terrain': 'plains',
            })
        })

        add_hexes(new_hexes, total_col, total_row);

    }

    function add_row(e) {
        let new_hexes = [];
        let total_col = parseInt($('.the_map').attr('data-total-col'));
        let total_row = parseInt($('.the_map').attr('data-total-row')) + 1;
        $('.add-hex-row:not(.add-hex-column)').each(function(e) {
            new_hexes.push({
                'column_hex': $(this).parent().attr('data-column'),
                'row_hex': $(this).attr('data-row'),
                'terrain': 'plains',
            })
        })

        add_hexes(new_hexes, total_col, total_row);

    }

    function add_hexes(hexes, columns, rows) {
        $.ajax({
            url: getHex.ajax_url,
            type: 'post',
            data: {
                action: 'add_hex',
                hexes: hexes,
                columns: columns,
                rows: rows,
                post_id: $('.map-container').attr('data-hexmap'),
                coords: {
                    'top': $('.the_map').css('top'),
                    'left': $('.the_map').css('left'),
                }
            },
            beforeSend: function() {
                $('.add-hex-column:not(.add-hex-row)').unbind('click');
                $('.add-hex-row:not(.add-hex-column)').unbind('click');
                $('.add-hex-row.add-hex-column').unbind('click');
                $('.map-container').addClass('loading-new');
            },
            success: function(html) {
                $('.map-container').removeClass('loading-new');
                $('.the_map').remove();
                $('.map-container').append(html);
                arrange_hexes();
                $('.add-hex-column:not(.add-hex-row)').on('click', add_column);
                $('div:not(.basic-information)>.hex-top:not(.hidden, .add-hex, .void)').on('click', load_hex);
                $('.add-hex-row:not(.add-hex-column)').on('click', add_row);
                $('.add-hex-row.add-hex-column').on('click', add_row_column);
            },
            fail: function() {
                console.log("Ajax request failed")
            }
        });
    }

})