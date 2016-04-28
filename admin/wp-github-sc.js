(function() {


    tinymce.create('tinymce.plugins.WpGithub', {
        init : function(ed, url) {
            values = [
                { text: 'Github Profile', value: 'profile' },
                { text: 'Clone Utilities', value: 'clone' },
                { text: 'Last 10 repos', value: 'repos' },
                { text: 'Last 10 commits', value: 'commits' },
                { text: 'last 10 commits from a repo', value: 'commits10' },
                { text: 'last 10 issues', value: 'issues' },
                { text: 'last 10 issues from a repo', value: 'issues10' },
                { text: 'single issue', value: 'issue' },
                { text: 'last 10 pull request', value: 'pulls' },
                { text: 'last 10 gists', value: 'gists' },
                { text: 'releases from repo', value: 'releases' },
                { text: 'latest release', value: 'releaseslatest' },
                { text: 'Display file content', value: 'contents' }
            ];
            ed.addButton('wpgithub', {
                type: 'listbox',
                title : 'Insert wp-github shortcode',
                cmd : 'wpgithub',
                image : url + '/cat6.png',
                values: values,
                onselect: function(e) {
                    shorcode = this.value();
                    jQuery.post(
                        ajaxurl,
                        {
                            'action': 'get_wpgithub_shortcodes',
                            'param' : shorcode
                        },
                        function(response){
                            tinymce.execCommand('mceInsertContent', false, response);
                        }
                    );

                }

            });

            //Insert ShortCode
            ed.addCommand('wpgithub', function() {
                var selected_text = ed.selection.getContent();
                var return_text = '';
                return_text =  selected_text ;
                ed.execCommand('mceInsertContent', 0, return_text);
            });

        }
        // ... Hidden code
    });
    // Register plugin
    tinymce.PluginManager.add( 'wpgithub', tinymce.plugins.WpGithub );
})();