
        
        
            function initGlobalInputValidation() {

                const inputs = document.querySelectorAll("[data-sql-check]");

                inputs.forEach(function(input) {

                    const field = input.closest(
                        ".kd-root-field, .form-group, .kd-form-field"
                    );

                    if (!field) {
                        return;
                    }

                    let errorBox = field.querySelector(".kd-global-input-error");

                    if (!errorBox) {

                        errorBox = document.createElement("div");

                        errorBox.className = "kd-global-input-error";

                        field.appendChild(errorBox);
                    }


                    function checkInput() {

                        const value = input.value;

                        /*
                         * IMPORTANT:
                         * Apostrophe (') is allowed.
                         * Double quote (") is allowed.
                         * Backtick (`) is allowed.
                         * SQL safety is handled by PHP prepared statements.
                         */

                        input.classList.remove(
                            "kd-global-input-error-state"
                        );

                        errorBox.textContent = "";

                        errorBox.classList.remove(
                            "kd-global-error-show"
                        );

                        input.setCustomValidity("");

                        return true;
                    }


                    input.addEventListener("input", checkInput);

                    input.addEventListener("paste", function() {

                        setTimeout(function() {
                            checkInput();
                        }, 0);

                    });

                });
            }


            /* Run automatically */
            document.addEventListener(
                "DOMContentLoaded",
                initGlobalInputValidation
            );
        
            const haamer_id_btn = document.getElementById("haamer_id_adase");
            let dash_box_slie = document.getElementById("left_section_box");
            let close_linw969 = document.getElementById("close_linw969");
            let haw_linw969 = document.getElementById("haw_linw969");

            // if koi btn pa click kar to ya chale ga 
            haamer_id_btn.addEventListener("click", () => {

                dash_box_slie.classList.toggle("dashbord_revile_gl");
                haamer_id_btn.classList.toggle("haamer_btn_nikw5");

                if (haamer_id_btn.classList.contains("haamer_btn_nikw5")) {

                    close_linw969.style.display = "flex";
                    haw_linw969.style.display = "none";

                } else {

                    close_linw969.style.display = "none";
                    haw_linw969.style.display = "flex";

                }

            });


            document.addEventListener("DOMContentLoaded", function () {

    const haamer_id_btn = document.getElementById("haamer_id_adase");
    const dash_box_slie = document.getElementById("left_section_box");
    const close_linw969 = document.getElementById("close_linw969");
    const haw_linw969 = document.getElementById("haw_linw969");

    if (
        !haamer_id_btn ||
        !dash_box_slie ||
        !close_linw969 ||
        !haw_linw969
    ) {
        return;
    }

    // Initial state
    close_linw969.style.display = "none";
    haw_linw969.style.display = "flex";


    haamer_id_btn.addEventListener("click", function (e) {

        e.preventDefault();
        e.stopPropagation();

        // Sidebar toggle
        dash_box_slie.classList.toggle("dashbord_revile_gl");
        haamer_id_btn.classList.toggle("haamer_btn_nikw5");

        const isOpen = haamer_id_btn.classList.contains("haamer_btn_nikw5");

        if (isOpen) {

            close_linw969.style.display = "flex";
            haw_linw969.style.display = "none";

        } else {

            close_linw969.style.display = "none";
            haw_linw969.style.display = "flex";

        }

    }, true);

});

            function show_this_box(el) {

                let overview_box_show = document.getElementById("overview_contect_box");
                let login_contect_box = document.getElementById("login_contect_box");
                let dashbord_contect_box = document.getElementById("dashbord_contect_box");
                let sms_contect_box = document.getElementById("sms_contect_box");
                let rcs_contect_box = document.getElementById("rcs_contect_box");
                let whatsapp_contect_box = document.getElementById("whatsapp_contect_box");
                let voice_contect_box = document.getElementById("voice_contect_box");
                let ivr_contect_box = document.getElementById("ivr_contect_box");
                let click_to_call_contect_box = document.getElementById("click_to_call_contect_box");
                let text_to_speech_contect_box = document.getElementById("text_to_speech_contect_box");
                let ai_agent_contect_box = document.getElementById("ai_agent_contect_box");
                let journeys_contect_box = document.getElementById("journeys_contect_box");


                // btn sole here 
                let overview_btn_124 = document.getElementById("overview_btn_124");
                let login_btn_124 = document.getElementById("login_btn_124");
                let dashbord_btn_124 = document.getElementById("dashbord_btn_124");

                let sms_btn_124 = document.getElementById("sms_btn_124");
                let rcs_btn_124 = document.getElementById("rcs_btn_124");
                let whatsapp_btn_124 = document.getElementById("whatsapp_btn_124");
                let voice_btn_124 = document.getElementById("voice_btn_124");
                let ivr_btn_124 = document.getElementById("ivr_btn_124");
                let click_to_call_btn_124 = document.getElementById("click_to_call_btn_124");
                let text_to_speech_btn_124 = document.getElementById("text_to_speech_btn_124");
                let ai_agent_btn_124 = document.getElementById("ai_agent_btn_124");

                let journeys_btn_124 = document.getElementById("journeys_btn_124");


                // if koi btn pa click kar to ya chale ga 
                haamer_id_btn.addEventListener("click", () => {

                    dash_box_slie.classList.toggle("dashbord_revile_gl");
                    haamer_id_btn.classList.toggle("haamer_btn_nikw5");

                    if (haamer_id_btn.classList.contains("haamer_btn_nikw5")) {

                        close_linw969.style.display = "flex";
                        haw_linw969.style.display = "none";

                    } else {

                        close_linw969.style.display = "none";
                        haw_linw969.style.display = "flex";

                    }

                });


                document.querySelectorAll('.subbox_link').forEach(item => {
                    item.classList.remove('active');

                    let svg = item.querySelector('.sub_set_iocn_svg');

                    if (svg) {
                        svg.classList.remove('svg_icon_color-active');
                    }
                });

                el.classList.add('active');

                let icon = el.querySelector('.sub_set_iocn_svg');

                if (icon) {
                    icon.classList.add('svg_icon_color-active');
                }

                // all sub btn 
                let contect__1 = document.getElementById("contect__1");
                let contect__2 = document.getElementById("contect__2");
                let contect__3 = document.getElementById("contect__3");
                let contect__4 = document.getElementById("contect__4");
                let contect__5 = document.getElementById("contect__5");
                let contect__6 = document.getElementById("contect__6");
                let contect__7 = document.getElementById("contect__7");
                let contect__8 = document.getElementById("contect__8");
                let contect__9 = document.getElementById("contect__9");
                let contect__10 = document.getElementById("contect__10");
                let contect__11 = document.getElementById("contect__11");
                let contect__12 = document.getElementById("contect__12");
                let contect__13 = document.getElementById("contect__13");
                let contect__14 = document.getElementById("contect__14");
                let contect__15 = document.getElementById("contect__15");
                let contect__16 = document.getElementById("contect__16");
                let contect__17 = document.getElementById("contect__17");
                let contect__18 = document.getElementById("contect__18");
                let contect__19 = document.getElementById("contect__19");
                let contect__20 = document.getElementById("contect__20");
                let contect__21 = document.getElementById("contect__21");
                let contect__22 = document.getElementById("contect__22");
                let contect__23 = document.getElementById("contect__23");
                let contect__24 = document.getElementById("contect__24");
                let contect__25 = document.getElementById("contect__25");
                let contect__26 = document.getElementById("contect__26");
                let contect__27 = document.getElementById("contect__27");
                let contect__28 = document.getElementById("contect__28");
                let contect__29 = document.getElementById("contect__29");
                let contect__30 = document.getElementById("contect__30");
                let contect__31 = document.getElementById("contect__31");
                let contect__32 = document.getElementById("contect__32");
                let contect__33 = document.getElementById("contect__33");
                let contect__34 = document.getElementById("contect__34");
                let contect__35 = document.getElementById("contect__35");
                let contect__36 = document.getElementById("contect__36");
                let contect__37 = document.getElementById("contect__37");
                let contect__38 = document.getElementById("contect__38");
                let contect__39 = document.getElementById("contect__39");
                let contect__40 = document.getElementById("contect__40");
                let contect__41 = document.getElementById("contect__41");
                let contect__42 = document.getElementById("contect__42");
                let contect__43 = document.getElementById("contect__43");
                let contect__44 = document.getElementById("contect__44");
                let contect__45 = document.getElementById("contect__45");
                let contect__46 = document.getElementById("contect__46");
                let contect__47 = document.getElementById("contect__47");
                let contect__48 = document.getElementById("contect__48");
                let contect__49 = document.getElementById("contect__49");
                let contect__50 = document.getElementById("contect__50");
                let contect__51 = document.getElementById("contect__51");
                let contect__52 = document.getElementById("contect__52");
                let contect__53 = document.getElementById("contect__53");
                let contect__54 = document.getElementById("contect__54");
                let contect__55 = document.getElementById("contect__55");
                let contect__56 = document.getElementById("contect__56");
                let contect__57 = document.getElementById("contect__57");
                let contect__58 = document.getElementById("contect__58");
                let contect__59 = document.getElementById("contect__59");
                let contect__60 = document.getElementById("contect__60");

                contect__1.classList.remove("active");
                contect__2.classList.remove("active");
                contect__3.classList.remove("active");
                contect__4.classList.remove("active");
                contect__5.classList.remove("active");
                contect__6.classList.remove("active");
                contect__7.classList.remove("active");
                contect__8.classList.remove("active");
                contect__9.classList.remove("active");
                contect__10.classList.remove("active");
                contect__11.classList.remove("active");
                contect__12.classList.remove("active");
                contect__13.classList.remove("active");
                contect__14.classList.remove("active");
                contect__15.classList.remove("active");
                contect__16.classList.remove("active");
                contect__17.classList.remove("active");
                contect__18.classList.remove("active");
                contect__19.classList.remove("active");
                contect__20.classList.remove("active");
                contect__21.classList.remove("active");
                contect__22.classList.remove("active");
                contect__23.classList.remove("active");
                contect__24.classList.remove("active");
                contect__25.classList.remove("active");
                contect__26.classList.remove("active");
                contect__27.classList.remove("active");
                contect__28.classList.remove("active");
                contect__29.classList.remove("active");
                contect__30.classList.remove("active");
                contect__31.classList.remove("active");
                contect__32.classList.remove("active");
                contect__33.classList.remove("active");
                contect__34.classList.remove("active");
                contect__35.classList.remove("active");
                contect__36.classList.remove("active");
                contect__37.classList.remove("active");
                contect__38.classList.remove("active");
                contect__39.classList.remove("active");
                contect__40.classList.remove("active");
                contect__41.classList.remove("active");
                contect__42.classList.remove("active");
                contect__43.classList.remove("active");
                contect__44.classList.remove("active");
                contect__45.classList.remove("active");
                contect__46.classList.remove("active");
                contect__47.classList.remove("active");
                contect__48.classList.remove("active");
                contect__49.classList.remove("active");
                contect__50.classList.remove("active");
                contect__51.classList.remove("active");
                contect__52.classList.remove("active");
                contect__53.classList.remove("active");
                contect__54.classList.remove("active");
                contect__55.classList.remove("active");
                contect__56.classList.remove("active");
                contect__57.classList.remove("active");
                contect__58.classList.remove("active");
                contect__59.classList.remove("active");
                contect__60.classList.remove("active");
                // all sub boxs 
                let contect_box_1 = document.getElementById("contect_1_contect_box");
                let contect_box_2 = document.getElementById("contect_2_contect_box");
                let contect_box_3 = document.getElementById("contect_3_contect_box");
                let contect_box_4 = document.getElementById("contect_4_contect_box");
                let contect_box_5 = document.getElementById("contect_5_contect_box");
                let contect_box_6 = document.getElementById("contect_6_contect_box");
                let contect_box_7 = document.getElementById("contect_7_contect_box");
                let contect_box_8 = document.getElementById("contect_8_contect_box");
                let contect_box_9 = document.getElementById("contect_9_contect_box");
                let contect_box_10 = document.getElementById("contect_10_contect_box");
                let contect_box_11 = document.getElementById("contect_11_contect_box");
                let contect_box_12 = document.getElementById("contect_12_contect_box");
                let contect_box_13 = document.getElementById("contect_13_contect_box");
                let contect_box_14 = document.getElementById("contect_14_contect_box");
                let contect_box_15 = document.getElementById("contect_15_contect_box");
                let contect_box_16 = document.getElementById("contect_16_contect_box");
                let contect_box_17 = document.getElementById("contect_17_contect_box");
                let contect_box_18 = document.getElementById("contect_18_contect_box");
                let contect_box_19 = document.getElementById("contect_19_contect_box");
                let contect_box_20 = document.getElementById("contect_20_contect_box");
                let contect_box_21 = document.getElementById("contect_21_contect_box");
                let contect_box_22 = document.getElementById("contect_22_contect_box");
                let contect_box_23 = document.getElementById("contect_23_contect_box");
                let contect_box_24 = document.getElementById("contect_24_contect_box");
                let contect_box_25 = document.getElementById("contect_25_contect_box");
                let contect_box_26 = document.getElementById("contect_26_contect_box");
                let contect_box_27 = document.getElementById("contect_27_contect_box");
                let contect_box_28 = document.getElementById("contect_28_contect_box");
                let contect_box_29 = document.getElementById("contect_29_contect_box");
                let contect_box_30 = document.getElementById("contect_30_contect_box");
                let contect_box_31 = document.getElementById("contect_31_contect_box");
                let contect_box_32 = document.getElementById("contect_32_contect_box");
                let contect_box_33 = document.getElementById("contect_33_contect_box");
                let contect_box_34 = document.getElementById("contect_34_contect_box");
                let contect_box_35 = document.getElementById("contect_35_contect_box");
                let contect_box_36 = document.getElementById("contect_36_contect_box");
                let contect_box_37 = document.getElementById("contect_37_contect_box");
                let contect_box_38 = document.getElementById("contect_38_contect_box");
                let contect_box_39 = document.getElementById("contect_39_contect_box");
                let contect_box_40 = document.getElementById("contect_40_contect_box");
                let contect_box_41 = document.getElementById("contect_41_contect_box");
                let contect_box_42 = document.getElementById("contect_42_contect_box");
                let contect_box_43 = document.getElementById("contect_43_contect_box");
                let contect_box_44 = document.getElementById("contect_44_contect_box");
                let contect_box_45 = document.getElementById("contect_45_contect_box");
                let contect_box_46 = document.getElementById("contect_46_contect_box");
                let contect_box_47 = document.getElementById("contect_47_contect_box");
                let contect_box_48 = document.getElementById("contect_48_contect_box");
                let contect_box_49 = document.getElementById("contect_49_contect_box");
                let contect_box_50 = document.getElementById("contect_50_contect_box");
                let contect_box_51 = document.getElementById("contect_51_contect_box");
                let contect_box_52 = document.getElementById("contect_52_contect_box");
                let contect_box_53 = document.getElementById("contect_53_contect_box");
                let contect_box_54 = document.getElementById("contect_54_contect_box");
                let contect_box_55 = document.getElementById("contect_55_contect_box");
                let contect_box_56 = document.getElementById("contect_56_contect_box");
                let contect_box_57 = document.getElementById("contect_57_contect_box");
                let contect_box_58 = document.getElementById("contect_58_contect_box");
                let contect_box_59 = document.getElementById("contect_59_contect_box");
                let contect_box_60 = document.getElementById("contect_60_contect_box");


                contect_box_1.classList.remove("show_active");
                contect_box_2.classList.remove("show_active");
                contect_box_3.classList.remove("show_active");
                contect_box_4.classList.remove("show_active");
                contect_box_5.classList.remove("show_active");
                contect_box_6.classList.remove("show_active");
                contect_box_7.classList.remove("show_active");
                contect_box_8.classList.remove("show_active");
                contect_box_9.classList.remove("show_active");
                contect_box_10.classList.remove("show_active");
                contect_box_11.classList.remove("show_active");
                contect_box_12.classList.remove("show_active");
                contect_box_13.classList.remove("show_active");
                contect_box_14.classList.remove("show_active");
                contect_box_15.classList.remove("show_active");
                contect_box_16.classList.remove("show_active");
                contect_box_17.classList.remove("show_active");
                contect_box_18.classList.remove("show_active");
                contect_box_19.classList.remove("show_active");
                contect_box_20.classList.remove("show_active");
                contect_box_21.classList.remove("show_active");
                contect_box_22.classList.remove("show_active");
                contect_box_23.classList.remove("show_active");
                contect_box_24.classList.remove("show_active");
                contect_box_25.classList.remove("show_active");
                contect_box_26.classList.remove("show_active");
                contect_box_27.classList.remove("show_active");
                contect_box_28.classList.remove("show_active");
                contect_box_29.classList.remove("show_active");
                contect_box_30.classList.remove("show_active");
                contect_box_31.classList.remove("show_active");
                contect_box_32.classList.remove("show_active");
                contect_box_33.classList.remove("show_active");
                contect_box_34.classList.remove("show_active");
                contect_box_35.classList.remove("show_active");
                contect_box_36.classList.remove("show_active");
                contect_box_37.classList.remove("show_active");
                contect_box_38.classList.remove("show_active");
                contect_box_39.classList.remove("show_active");
                contect_box_40.classList.remove("show_active");
                contect_box_41.classList.remove("show_active");
                contect_box_42.classList.remove("show_active");
                contect_box_43.classList.remove("show_active");
                contect_box_44.classList.remove("show_active");
                contect_box_45.classList.remove("show_active");
                contect_box_46.classList.remove("show_active");
                contect_box_47.classList.remove("show_active");
                contect_box_48.classList.remove("show_active");
                contect_box_49.classList.remove("show_active");
                contect_box_50.classList.remove("show_active");
                contect_box_51.classList.remove("show_active");
                contect_box_52.classList.remove("show_active");
                contect_box_53.classList.remove("show_active");
                contect_box_54.classList.remove("show_active");
                contect_box_55.classList.remove("show_active");
                contect_box_56.classList.remove("show_active");
                contect_box_57.classList.remove("show_active");
                contect_box_58.classList.remove("show_active");
                contect_box_59.classList.remove("show_active");
                contect_box_60.classList.remove("show_active");

                // set defulte none on all box 
                overview_box_show.classList.remove("show_active")
                login_contect_box.classList.remove("show_active")
                dashbord_contect_box.classList.remove("show_active")
                sms_contect_box.classList.remove("show_active")
                rcs_contect_box.classList.remove("show_active")
                whatsapp_contect_box.classList.remove("show_active")
                voice_contect_box.classList.remove("show_active")
                ivr_contect_box.classList.remove("show_active")
                click_to_call_contect_box.classList.remove("show_active")
                text_to_speech_contect_box.classList.remove("show_active")
                ai_agent_contect_box.classList.remove("show_active")
                journeys_contect_box.classList.remove("show_active")

                let value = el.dataset.set;

                if (value === "overview") {
                    overview_box_show.classList.add("show_active")

                    dash_box_slie.classList.toggle("dashbord_revile_gl");
                    haamer_id_btn.classList.toggle("haamer_btn_nikw5");

                    if (haamer_id_btn.classList.contains("haamer_btn_nikw5")) {

                        close_linw969.style.display = "flex";
                        haw_linw969.style.display = "none";

                    } else {

                        close_linw969.style.display = "none";
                        haw_linw969.style.display = "flex";

                    }
                }

                if (value === "login") {
                    login_contect_box.classList.add("show_active")
                }

                if (value === "dashbord") {
                    dashbord_contect_box.classList.add("show_active")
                }

                if (value === "sms") {
                    sms_contect_box.classList.add("show_active")
                }

                if (value === "rcs") {
                    rcs_contect_box.classList.add("show_active")
                }

                if (value === "whatsapp") {
                    whatsapp_contect_box.classList.add("show_active")
                }

                if (value === "voice") {
                    voice_contect_box.classList.add("show_active")
                }

                if (value === "ivr") {
                    ivr_contect_box.classList.add("show_active")
                }

                if (value === "click_to_call") {
                    click_to_call_contect_box.classList.add("show_active")
                }

                if (value === "text_to_speech") {
                    text_to_speech_contect_box.classList.add("show_active")
                }
                if (value === "ai_agent") {
                    ai_agent_contect_box.classList.add("show_active")
                }
                if (value === "journeys") {
                    journeys_contect_box.classList.add("show_active")
                }
            }


            function show_sub_link(el) {

                let sms_sub_box = document.getElementById("sms_sub_box");
                let rcs_sub_box = document.getElementById("rcs_sub_box");
                let whatsapp_sub_box = document.getElementById("whatsapp_sub_box");
                let voice_sub_box = document.getElementById("voice_sub_box");
                let ivr_sub_box = document.getElementById("ivr_sub_box");
                let click_to_call_sub_box = document.getElementById("click_to_call_sub_box");
                let text_to_speech_sub_box = document.getElementById("text_to_speech_sub_box");
                let ai_agent_sub_box = document.getElementById("ai_agent_sub_box");
                let journeys_sub_box = document.getElementById("journeys_sub_box");

                let sub_value = el.dataset.set;

                sms_sub_box.classList.remove("sub_links_of_show_box");
                rcs_sub_box.classList.remove("sub_links_of_show_box");
                whatsapp_sub_box.classList.remove("sub_links_of_show_box");
                voice_sub_box.classList.remove("sub_links_of_show_box");
                ivr_sub_box.classList.remove("sub_links_of_show_box");
                click_to_call_sub_box.classList.remove("sub_links_of_show_box");
                text_to_speech_sub_box.classList.remove("sub_links_of_show_box");
                ai_agent_sub_box.classList.remove("sub_links_of_show_box");
                journeys_sub_box.classList.remove("sub_links_of_show_box");



                // sub_links_of_show_box
                if (sub_value === "sms") {
                    sms_sub_box.classList.toggle("sub_links_of_show_box")
                }

                if (sub_value === "rcs") {
                    rcs_sub_box.classList.toggle("sub_links_of_show_box")

                }

                if (sub_value === "whatsapp") {
                    whatsapp_sub_box.classList.toggle("sub_links_of_show_box")

                }

                if (sub_value === "voice") {
                    voice_sub_box.classList.toggle("sub_links_of_show_box")

                }

                if (sub_value === "ivr") {
                    ivr_sub_box.classList.toggle("sub_links_of_show_box")

                }

                if (sub_value === "click_to_call") {
                    click_to_call_sub_box.classList.toggle("sub_links_of_show_box")

                }

                if (sub_value === "text_to_speech") {
                    text_to_speech_sub_box.classList.toggle("sub_links_of_show_box")

                }
                if (sub_value === "ai_agent") {
                    ai_agent_sub_box.classList.toggle("sub_links_of_show_box")

                }
                if (sub_value === "journeys") {
                    journeys_sub_box.classList.toggle("sub_links_of_show_box")

                }

            }


            function link_sole_action(el) {

                let box_id = el.dataset.set;

                // Jis submenu ke andar clicked item hai
                let parent_box = el.closest(".sub_links_of_allawn");

                if (parent_box) {

                    // Pehle sabhi items se active hatao
                    parent_box.querySelectorAll(".sole78m").forEach(function(item) {
                        item.classList.remove("active");
                    });

                    // Sirf clicked item ko active karo
                    el.classList.add("active");
                }

                // all sub boxs 
                let contect_box_1 = document.getElementById("contect_1_contect_box");
                let contect_box_2 = document.getElementById("contect_2_contect_box");
                let contect_box_3 = document.getElementById("contect_3_contect_box");
                let contect_box_4 = document.getElementById("contect_4_contect_box");
                let contect_box_5 = document.getElementById("contect_5_contect_box");
                let contect_box_6 = document.getElementById("contect_6_contect_box");
                let contect_box_7 = document.getElementById("contect_7_contect_box");
                let contect_box_8 = document.getElementById("contect_8_contect_box");
                let contect_box_9 = document.getElementById("contect_9_contect_box");
                let contect_box_10 = document.getElementById("contect_10_contect_box");
                let contect_box_11 = document.getElementById("contect_11_contect_box");
                let contect_box_12 = document.getElementById("contect_12_contect_box");
                let contect_box_13 = document.getElementById("contect_13_contect_box");
                let contect_box_14 = document.getElementById("contect_14_contect_box");
                let contect_box_15 = document.getElementById("contect_15_contect_box");
                let contect_box_16 = document.getElementById("contect_16_contect_box");
                let contect_box_17 = document.getElementById("contect_17_contect_box");
                let contect_box_18 = document.getElementById("contect_18_contect_box");
                let contect_box_19 = document.getElementById("contect_19_contect_box");
                let contect_box_20 = document.getElementById("contect_20_contect_box");
                let contect_box_21 = document.getElementById("contect_21_contect_box");
                let contect_box_22 = document.getElementById("contect_22_contect_box");
                let contect_box_23 = document.getElementById("contect_23_contect_box");
                let contect_box_24 = document.getElementById("contect_24_contect_box");
                let contect_box_25 = document.getElementById("contect_25_contect_box");
                let contect_box_26 = document.getElementById("contect_26_contect_box");
                let contect_box_27 = document.getElementById("contect_27_contect_box");
                let contect_box_28 = document.getElementById("contect_28_contect_box");
                let contect_box_29 = document.getElementById("contect_29_contect_box");
                let contect_box_30 = document.getElementById("contect_30_contect_box");
                let contect_box_31 = document.getElementById("contect_31_contect_box");
                let contect_box_32 = document.getElementById("contect_32_contect_box");
                let contect_box_33 = document.getElementById("contect_33_contect_box");
                let contect_box_34 = document.getElementById("contect_34_contect_box");
                let contect_box_35 = document.getElementById("contect_35_contect_box");
                let contect_box_36 = document.getElementById("contect_36_contect_box");
                let contect_box_37 = document.getElementById("contect_37_contect_box");
                let contect_box_38 = document.getElementById("contect_38_contect_box");
                let contect_box_39 = document.getElementById("contect_39_contect_box");
                let contect_box_40 = document.getElementById("contect_40_contect_box");
                let contect_box_41 = document.getElementById("contect_41_contect_box");
                let contect_box_42 = document.getElementById("contect_42_contect_box");
                let contect_box_43 = document.getElementById("contect_43_contect_box");
                let contect_box_44 = document.getElementById("contect_44_contect_box");
                let contect_box_45 = document.getElementById("contect_45_contect_box");
                let contect_box_46 = document.getElementById("contect_46_contect_box");
                let contect_box_47 = document.getElementById("contect_47_contect_box");
                let contect_box_48 = document.getElementById("contect_48_contect_box");
                let contect_box_49 = document.getElementById("contect_49_contect_box");
                let contect_box_50 = document.getElementById("contect_50_contect_box");
                let contect_box_51 = document.getElementById("contect_51_contect_box");
                let contect_box_52 = document.getElementById("contect_52_contect_box");
                let contect_box_53 = document.getElementById("contect_53_contect_box");
                let contect_box_54 = document.getElementById("contect_54_contect_box");
                let contect_box_55 = document.getElementById("contect_55_contect_box");
                let contect_box_56 = document.getElementById("contect_56_contect_box");
                let contect_box_57 = document.getElementById("contect_57_contect_box");
                let contect_box_58 = document.getElementById("contect_58_contect_box");
                let contect_box_59 = document.getElementById("contect_59_contect_box");
                let contect_box_60 = document.getElementById("contect_60_contect_box");


                contect_box_1.classList.remove("show_active");
                contect_box_2.classList.remove("show_active");
                contect_box_3.classList.remove("show_active");
                contect_box_4.classList.remove("show_active");
                contect_box_5.classList.remove("show_active");
                contect_box_6.classList.remove("show_active");
                contect_box_7.classList.remove("show_active");
                contect_box_8.classList.remove("show_active");
                contect_box_9.classList.remove("show_active");
                contect_box_10.classList.remove("show_active");
                contect_box_11.classList.remove("show_active");
                contect_box_12.classList.remove("show_active");
                contect_box_13.classList.remove("show_active");
                contect_box_14.classList.remove("show_active");
                contect_box_15.classList.remove("show_active");
                contect_box_16.classList.remove("show_active");
                contect_box_17.classList.remove("show_active");
                contect_box_18.classList.remove("show_active");
                contect_box_19.classList.remove("show_active");
                contect_box_20.classList.remove("show_active");
                contect_box_21.classList.remove("show_active");
                contect_box_22.classList.remove("show_active");
                contect_box_23.classList.remove("show_active");
                contect_box_24.classList.remove("show_active");
                contect_box_25.classList.remove("show_active");
                contect_box_26.classList.remove("show_active");
                contect_box_27.classList.remove("show_active");
                contect_box_28.classList.remove("show_active");
                contect_box_29.classList.remove("show_active");
                contect_box_30.classList.remove("show_active");
                contect_box_31.classList.remove("show_active");
                contect_box_32.classList.remove("show_active");
                contect_box_33.classList.remove("show_active");
                contect_box_34.classList.remove("show_active");
                contect_box_35.classList.remove("show_active");
                contect_box_36.classList.remove("show_active");
                contect_box_37.classList.remove("show_active");
                contect_box_38.classList.remove("show_active");
                contect_box_39.classList.remove("show_active");
                contect_box_40.classList.remove("show_active");
                contect_box_41.classList.remove("show_active");
                contect_box_42.classList.remove("show_active");
                contect_box_43.classList.remove("show_active");
                contect_box_44.classList.remove("show_active");
                contect_box_45.classList.remove("show_active");
                contect_box_46.classList.remove("show_active");
                contect_box_47.classList.remove("show_active");
                contect_box_48.classList.remove("show_active");
                contect_box_49.classList.remove("show_active");
                contect_box_50.classList.remove("show_active");
                contect_box_51.classList.remove("show_active");
                contect_box_52.classList.remove("show_active");
                contect_box_53.classList.remove("show_active");
                contect_box_54.classList.remove("show_active");
                contect_box_55.classList.remove("show_active");
                contect_box_56.classList.remove("show_active");
                contect_box_57.classList.remove("show_active");
                contect_box_58.classList.remove("show_active");
                contect_box_59.classList.remove("show_active");
                contect_box_60.classList.remove("show_active");

                let overview_box_show = document.getElementById("overview_contect_box");
                let login_contect_box = document.getElementById("login_contect_box");
                let dashbord_contect_box = document.getElementById("dashbord_contect_box");
                let sms_contect_box = document.getElementById("sms_contect_box");
                let rcs_contect_box = document.getElementById("rcs_contect_box");
                let whatsapp_contect_box = document.getElementById("whatsapp_contect_box");
                let voice_contect_box = document.getElementById("voice_contect_box");
                let ivr_contect_box = document.getElementById("ivr_contect_box");
                let click_to_call_contect_box = document.getElementById("click_to_call_contect_box");
                let text_to_speech_contect_box = document.getElementById("text_to_speech_contect_box");
                let ai_agent_contect_box = document.getElementById("ai_agent_contect_box");
                let journeys_contect_box = document.getElementById("journeys_contect_box");

                // set defulte none on all box 
                overview_box_show.classList.remove("show_active")
                login_contect_box.classList.remove("show_active")
                dashbord_contect_box.classList.remove("show_active")
                sms_contect_box.classList.remove("show_active")
                rcs_contect_box.classList.remove("show_active")
                whatsapp_contect_box.classList.remove("show_active")
                voice_contect_box.classList.remove("show_active")
                ivr_contect_box.classList.remove("show_active")
                click_to_call_contect_box.classList.remove("show_active")
                text_to_speech_contect_box.classList.remove("show_active")
                ai_agent_contect_box.classList.remove("show_active")
                journeys_contect_box.classList.remove("show_active")

                contect_box_1.classList.remove("show_active");
                contect_box_2.classList.remove("show_active");
                contect_box_3.classList.remove("show_active");
                contect_box_4.classList.remove("show_active");
                contect_box_5.classList.remove("show_active");
                contect_box_6.classList.remove("show_active");
                contect_box_7.classList.remove("show_active");
                contect_box_8.classList.remove("show_active");
                contect_box_9.classList.remove("show_active");
                contect_box_10.classList.remove("show_active");
                contect_box_11.classList.remove("show_active");
                contect_box_12.classList.remove("show_active");
                contect_box_13.classList.remove("show_active");
                contect_box_14.classList.remove("show_active");
                contect_box_15.classList.remove("show_active");
                contect_box_16.classList.remove("show_active");
                contect_box_17.classList.remove("show_active");
                contect_box_18.classList.remove("show_active");
                contect_box_19.classList.remove("show_active");
                contect_box_20.classList.remove("show_active");
                contect_box_21.classList.remove("show_active");
                contect_box_22.classList.remove("show_active");
                contect_box_23.classList.remove("show_active");
                contect_box_24.classList.remove("show_active");
                contect_box_25.classList.remove("show_active");
                contect_box_26.classList.remove("show_active");
                contect_box_27.classList.remove("show_active");
                contect_box_28.classList.remove("show_active");
                contect_box_29.classList.remove("show_active");
                contect_box_30.classList.remove("show_active");
                contect_box_31.classList.remove("show_active");
                contect_box_32.classList.remove("show_active");
                contect_box_33.classList.remove("show_active");
                contect_box_34.classList.remove("show_active");
                contect_box_35.classList.remove("show_active");
                contect_box_36.classList.remove("show_active");
                contect_box_37.classList.remove("show_active");
                contect_box_38.classList.remove("show_active");
                contect_box_39.classList.remove("show_active");
                contect_box_40.classList.remove("show_active");
                contect_box_41.classList.remove("show_active");
                contect_box_42.classList.remove("show_active");
                contect_box_43.classList.remove("show_active");
                contect_box_44.classList.remove("show_active");
                contect_box_45.classList.remove("show_active");
                contect_box_46.classList.remove("show_active");
                contect_box_47.classList.remove("show_active");
                contect_box_48.classList.remove("show_active");
                contect_box_49.classList.remove("show_active");
                contect_box_50.classList.remove("show_active");
                contect_box_51.classList.remove("show_active");
                contect_box_52.classList.remove("show_active");
                contect_box_53.classList.remove("show_active");
                contect_box_54.classList.remove("show_active");
                contect_box_55.classList.remove("show_active");
                contect_box_56.classList.remove("show_active");
                contect_box_57.classList.remove("show_active");
                contect_box_58.classList.remove("show_active");
                contect_box_59.classList.remove("show_active");
                contect_box_60.classList.remove("show_active");




                if (box_id === "contect-1") {
                    contect_box_1.classList.toggle("show_active");
                }

                if (box_id === "contect-2") {
                    contect_box_2.classList.toggle("show_active");
                }

                if (box_id === "contect-3") {
                    contect_box_3.classList.toggle("show_active");
                }

                if (box_id === "contect-4") {
                    contect_box_4.classList.toggle("show_active");
                }

                if (box_id === "contect-5") {
                    contect_box_5.classList.toggle("show_active");
                }

                if (box_id === "contect-6") {
                    contect_box_6.classList.toggle("show_active");
                }

                if (box_id === "contect-7") {
                    contect_box_7.classList.toggle("show_active");
                }

                if (box_id === "contect-8") {
                    contect_box_8.classList.toggle("show_active");
                }

                if (box_id === "contect-9") {
                    contect_box_9.classList.toggle("show_active");
                }

                if (box_id === "contect-10") {
                    contect_box_10.classList.toggle("show_active");
                }

                if (box_id === "contect-11") {
                    contect_box_11.classList.toggle("show_active");
                }

                if (box_id === "contect-12") {
                    contect_box_12.classList.toggle("show_active");
                }

                if (box_id === "contect-13") {
                    contect_box_13.classList.toggle("show_active");
                }

                if (box_id === "contect-14") {
                    contect_box_14.classList.toggle("show_active");
                }

                if (box_id === "contect-15") {
                    contect_box_15.classList.toggle("show_active");
                }

                if (box_id === "contect-16") {
                    contect_box_16.classList.toggle("show_active");
                }

                if (box_id === "contect-17") {
                    contect_box_17.classList.toggle("show_active");
                }

                if (box_id === "contect-18") {
                    contect_box_18.classList.toggle("show_active");
                }

                if (box_id === "contect-19") {
                    contect_box_19.classList.toggle("show_active");
                }

                if (box_id === "contect-20") {
                    contect_box_20.classList.toggle("show_active");
                }

                if (box_id === "contect-21") {
                    contect_box_21.classList.toggle("show_active");
                }

                if (box_id === "contect-22") {
                    contect_box_22.classList.toggle("show_active");
                }

                if (box_id === "contect-23") {
                    contect_box_23.classList.toggle("show_active");
                }

                if (box_id === "contect-24") {
                    contect_box_24.classList.toggle("show_active");
                }

                if (box_id === "contect-25") {
                    contect_box_25.classList.toggle("show_active");
                }

                if (box_id === "contect-26") {
                    contect_box_26.classList.toggle("show_active");
                }

                if (box_id === "contect-27") {
                    contect_box_27.classList.toggle("show_active");
                }

                if (box_id === "contect-28") {
                    contect_box_28.classList.toggle("show_active");
                }

                if (box_id === "contect-29") {
                    contect_box_29.classList.toggle("show_active");
                }

                if (box_id === "contect-30") {
                    contect_box_30.classList.toggle("show_active");
                }

                if (box_id === "contect-31") {
                    contect_box_31.classList.toggle("show_active");
                }

                if (box_id === "contect-32") {
                    contect_box_32.classList.toggle("show_active");
                }

                if (box_id === "contect-33") {
                    contect_box_33.classList.toggle("show_active");
                }

                if (box_id === "contect-34") {
                    contect_box_34.classList.toggle("show_active");
                }

                if (box_id === "contect-35") {
                    contect_box_35.classList.toggle("show_active");
                }

                if (box_id === "contect-36") {
                    contect_box_36.classList.toggle("show_active");
                }

                if (box_id === "contect-37") {
                    contect_box_37.classList.toggle("show_active");
                }

                if (box_id === "contect-38") {
                    contect_box_38.classList.toggle("show_active");
                }

                if (box_id === "contect-39") {
                    contect_box_39.classList.toggle("show_active");
                }

                if (box_id === "contect-40") {
                    contect_box_40.classList.toggle("show_active");
                }

                if (box_id === "contect-41") {
                    contect_box_41.classList.toggle("show_active");
                }

                if (box_id === "contect-42") {
                    contect_box_42.classList.toggle("show_active");
                }

                if (box_id === "contect-43") {
                    contect_box_43.classList.toggle("show_active");
                }

                if (box_id === "contect-44") {
                    contect_box_44.classList.toggle("show_active");
                }

                if (box_id === "contect-45") {
                    contect_box_45.classList.toggle("show_active");
                }

                if (box_id === "contect-46") {
                    contect_box_46.classList.toggle("show_active");
                }

                if (box_id === "contect-47") {
                    contect_box_47.classList.toggle("show_active");
                }

                if (box_id === "contect-48") {
                    contect_box_48.classList.toggle("show_active");
                }

                if (box_id === "contect-49") {
                    contect_box_49.classList.toggle("show_active");
                }

                if (box_id === "contect-50") {
                    contect_box_50.classList.toggle("show_active");
                }

                if (box_id === "contect-51") {
                    contect_box_51.classList.toggle("show_active");
                }

                if (box_id === "contect-52") {
                    contect_box_52.classList.toggle("show_active");
                }

                if (box_id === "contect-53") {
                    contect_box_53.classList.toggle("show_active");
                }

                if (box_id === "contect-54") {
                    contect_box_54.classList.toggle("show_active");
                }

                if (box_id === "contect-55") {
                    contect_box_55.classList.toggle("show_active");
                }

                if (box_id === "contect-56") {
                    contect_box_56.classList.toggle("show_active");
                }

                if (box_id === "contect-57") {
                    contect_box_57.classList.toggle("show_active");
                }

                if (box_id === "contect-58") {
                    contect_box_58.classList.toggle("show_active");
                }

                if (box_id === "contect-59") {
                    contect_box_59.classList.toggle("show_active");
                }

                if (box_id === "contect-60") {
                    contect_box_60.classList.toggle("show_active");
                }

            }


            function close_all_sub_links_ha() {
                let sms_sub_box = document.getElementById("sms_sub_box");
                let rcs_sub_box = document.getElementById("rcs_sub_box");
                let whatsapp_sub_box = document.getElementById("whatsapp_sub_box");
                let voice_sub_box = document.getElementById("voice_sub_box");
                let ivr_sub_box = document.getElementById("ivr_sub_box");
                let click_to_call_sub_box = document.getElementById("click_to_call_sub_box");
                let text_to_speech_sub_box = document.getElementById("text_to_speech_sub_box");
                let ai_agent_sub_box = document.getElementById("ai_agent_sub_box");
                let journeys_sub_box = document.getElementById("journeys_sub_box");

                sms_sub_box.classList.remove("sub_links_of_show_box");
                rcs_sub_box.classList.remove("sub_links_of_show_box");
                whatsapp_sub_box.classList.remove("sub_links_of_show_box");
                voice_sub_box.classList.remove("sub_links_of_show_box");
                ivr_sub_box.classList.remove("sub_links_of_show_box");
                click_to_call_sub_box.classList.remove("sub_links_of_show_box");
                text_to_speech_sub_box.classList.remove("sub_links_of_show_box");
                ai_agent_sub_box.classList.remove("sub_links_of_show_box");
                journeys_sub_box.classList.remove("sub_links_of_show_box");

            }
      
            function startOverviewCounters() {

                const sections = document.querySelectorAll(".counrt_grid_wer854_card");

                if (!sections.length) return;

                sections.forEach((section) => {

                    // Is section ke andar ke counters only
                    const counters = section.querySelectorAll(".qunik-count_version_2_onlyfor_appliction_page");

                    if (!counters.length) return;

                    const runCounter = (counter) => {

                        // Already started hai to dobara mat chalao
                        if (counter.dataset.counterStarted === "true") return;

                        counter.dataset.counterStarted = "true";

                        const target = parseFloat(counter.dataset.target);

                        if (isNaN(target)) return;

                        const suffix = counter.dataset.suffix || "";
                        const duration = 1800;
                        const start = performance.now();

                        function animateCounter(time) {

                            const progress = Math.min(
                                (time - start) / duration,
                                1
                            );

                            // Smooth animation
                            const ease = 1 - Math.pow(1 - progress, 3);

                            const currentValue = target * ease;

                            if (target % 1 !== 0) {

                                counter.textContent =
                                    currentValue.toFixed(1) + suffix;

                            } else {

                                counter.textContent =
                                    Math.floor(currentValue) + suffix;
                            }

                            if (progress < 1) {

                                requestAnimationFrame(animateCounter);

                            } else {

                                // Exact final value
                                counter.textContent =
                                    (target % 1 !== 0 ?
                                        target.toFixed(1) :
                                        target
                                    ) + suffix;
                            }
                        }

                        requestAnimationFrame(animateCounter);
                    };


                    // Section visible hone ka wait karega
                    const observer = new IntersectionObserver(
                        (entries, obs) => {

                            entries.forEach((entry) => {

                                if (!entry.isIntersecting) return;

                                // Sirf isi section ke counters
                                counters.forEach((counter) => {
                                    runCounter(counter);
                                });

                                // Ek baar chalne ke baad observer remove
                                obs.unobserve(entry.target);
                            });

                        }, {
                            threshold: 0.25
                        }
                    );

                    observer.observe(section);
                });
            }


            // Page load ke baad function run
            document.addEventListener("DOMContentLoaded", () => {
                startOverviewCounters();
            });
    