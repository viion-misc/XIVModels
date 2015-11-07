var xivmv = (function() {
    // Progress callback for JSON loading tracking
    var progress_callback = null;

    function set_progress_callback(callback) {
        progress_callback = callback;
    };

    var requestAnimFrame = window.requestAnimationFrame || window.mozRequestAnimationFrame || window.webkitRequestAnimationFrame;

    var lookup = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';

    function b64ToByteArray(b64) {
        var i, l, tmp, hasPadding, arr = [];

        if (i % 4 > 0) {
            throw 'Invalid string. Length must be a multiple of 4';
        }

        hasPadding = /=$/.test(b64);

        l = hasPadding ? b64.length - 4: b64.length;

        for (i = 0; i < l; i += 4) {
            tmp = (lookup.indexOf(b64[i]) << 18) | (lookup.indexOf(b64[i + 1]) << 12) | (lookup.indexOf(b64[i + 2]) << 6) | lookup.indexOf(b64[i + 3]);
            arr.push((tmp & 0xFF0000) >> 16);
            arr.push((tmp & 0xFF00) >> 8);
            arr.push(tmp & 0xFF);
        }

        if (hasPadding) {
            b64 = b64.substring(i, b64.indexOf('='));

            if (b64.length === 2) {
                tmp = (lookup.indexOf(b64[0]) << 2) | (lookup.indexOf(b64[1]) >> 4);
                arr.push(tmp & 0xFF);
            } else {
                tmp = (lookup.indexOf(b64[0]) << 10) | (lookup.indexOf(b64[1]) << 4) | (lookup.indexOf(b64[2]) >> 2);
                arr.push((tmp >> 8) & 0xFF);
                arr.push(tmp & 0xFF);
            }
        }

        return arr;
    }

    function decode_blob(data, alignment) {
        var output = null;
        try {
            var raw = new Uint8Array(b64ToByteArray(data))
            var inflate = new Zlib.Inflate(raw);
            output = inflate.decompress();
        } catch(e) {
            //console.log("Exception while decompressing data: " + e);
        }
        return output;
    };

    // Cache for JSON files
    var cache = {
        hash_table: {},

        get: function(name) {
            if (name in this.hash_table) {
                return this.hash_table[name];
            } else {
                this.hash_table[name] = {};
                var hash_table_entry = this.hash_table[name];

                var type = null;

                switch(name.slice(-4)) {
                    case ".tex":
                        type = "texture";
                        break;

                    case ".mdl":
                        type = "model";
                        break;

                    case "mtrl":
                        type = "material";
                        break;

                    default:
                        //console.log("Unknown file type: " + name);
                        break;
                }

                // fill the entry
                $.ajax({
                    url: name + ".json",
                    dataType: "json",
                    xhr: function() {
                        xhrObj = $.ajaxSettings.xhr();
                        xhrObj.addEventListener("progress", function(e) {
                            if (progressCallback) {
                                progressCallback(e, type, name);
                            }
                        }, false);
                        return xhrObj;
                    },
                    success: function(data) {
                        $.extend(hash_table_entry, data);

                        switch (type) {
                            case "texture":
                                hash_table_entry.decoded = {
                                    buffer: decode_blob(data.data, data.width * data.height)
                                };
                                break;

                            case "model":
                                hash_table_entry.decoded = {
                                    vertex_buffer: decode_blob(data.vertex_buffer),
                                    index_buffer: decode_blob(data.index_buffer)
                                };
                                break;

                            default:
                                break;
                        }
                        //console.log(type + " loaded: " + name);
                    }
                });

                return hash_table_entry;
            }
        }
    };

    function float16_to_float(h) {
        var s = (h & 0x8000) >> 15;
        var e = (h & 0x7C00) >> 10;
        var f = h & 0x03FF;

        if(e == 0) {
            return (s?-1:1) * Math.pow(2,-14) * (f/Math.pow(2, 10));
        } else if (e == 0x1F) {
            return f?NaN:((s?-1:1)*Infinity);
        }

        return (s?-1:1) * Math.pow(2, e-15) * (1+(f/Math.pow(2, 10)));
    }

    function create_texture(name) {
        var self = {
            json: cache.get(name),

            buffer_id: null,

            init_buffer: function(gl) {
                var json = this.json;

                this.buffer_id = gl.createTexture();
                gl.bindTexture(gl.TEXTURE_2D, this.buffer_id);

                switch(json.type) {
                    case "DXT1":
                        gl.compressedTexImage2D(gl.TEXTURE_2D,
                                        0,
                                        gl.extensions.compressedTextureS3tc.COMPRESSED_RGBA_S3TC_DXT1_EXT,
                                        json.width,
                                        json.height,
                                        0,
                                        json.decoded.buffer);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
                        break;

                    case "DXT5":
                        gl.compressedTexImage2D(gl.TEXTURE_2D,
                                        0,
                                        gl.extensions.compressedTextureS3tc.COMPRESSED_RGBA_S3TC_DXT5_EXT,
                                        json.width,
                                        json.height,
                                        0,
                                        json.decoded.buffer);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
                        break;

                    case "RGB8A8":
                        gl.texImage2D(gl.TEXTURE_2D,
                                        0,
                                        gl.RGBA,
                                        json.width,
                                        json.height,
                                        0,
                                        gl.RGBA,
                                        gl.UNSIGNED_BYTE,
                                        json.decoded.buffer);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
                        break;

                    case "RGBAF":
                        gl.texImage2D(gl.TEXTURE_2D,
                                        0,
                                        gl.RGBA,
                                        json.width,
                                        json.height,
                                        0,
                                        gl.RGBA,
                                        gl.FLOAT,
                                        new Float32Array(json.decoded.buffer.buffer));
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.NEAREST);
                        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.NEAREST);
                        break;

                    default:
                        throw "Unsupported texture format: " + json.type;
                }
                gl.bindTexture(gl.TEXTURE_2D, null);
                return true;
            },

            bind: function(gl, bind_point, index) {
                var json = this.json;

                if ($.isEmptyObject(json)) {
                    return false;
                }
                if (gl === null) {
                    throw "init_buffer called with empty gl";
                }

                if (this.buffer_id === null) {
                    if (!this.init_buffer(gl)) {
                        return false;
                    }
                }

                gl.activeTexture(gl["TEXTURE" + index]);
                gl.bindTexture(gl.TEXTURE_2D, this.buffer_id);
                gl.uniform1i(bind_point, index);

                return true;
            }
        };

        return {
            d: self,

            bind: self.bind.bind(self)
        }
    };

    function create_material(name) {
        var self = {
            json: cache.get(name),

            textures: {
                diffuse: null,
                specular: null,
                normal: null,
                table: null,
                mask: null
            },

            get_texture: function(name) {
                var json = this.json;

                if ($.isEmptyObject(json)) {
                    return null;
                }

                if (!name in this.textures) {
                    throw "get_texture called with invalid tex_name: " + name;
                }

                if (this.textures[name] === null) {
                    if (name in json.components) {
                        this.textures[name] = create_texture(json.components[name]);
                    }
                }

                return this.textures[name];
            }
        };

        return  {
            d: self,

            get_texture: self.get_texture.bind(self)
        }
    };

    function create_model(name) {
        var self = {
            json: cache.get(name),

            vertex_buffer_id: null,
            index_buffer_id: null,

            model_matrix: mat4.create(),

            new_material_version: 1, // default
            material_version: null,
            materials: null,

            set_model_matrix: function(model_matrix) {
                this.model_matrix = mat4.clone(model_matrix);
            },

            set_material_version: function(version_number) {
                this.new_material_version = version_number;
            },

            init_materials: function () {
                var json = this.json;
                this.materials = [];

                for (var i = 0; i < json.meshes.length; i++) {
                    var mesh = json.meshes[i];
                    var possible_material_names = json.materials[mesh.material];
                    if (this.new_material_version in possible_material_names) {
                        this.materials.push(create_material(possible_material_names[this.new_material_version]));
                    } else {
                    	if (!$.isEmptyObject(possible_material_names)) {
	                        var random_id;
	                        for (random_id in possible_material_names) break;
	                        this.materials.push(create_material(possible_material_names[random_id]));
                        } else {
                        	this.materials.push(null);
                        }
                    }
                }

                this.new_material_version = null;
            },

            init_buffers: function(gl) {
                var json = this.json;

                this.vertex_buffer_id = gl.createBuffer();
                gl.bindBuffer(gl.ARRAY_BUFFER, this.vertex_buffer_id);
                gl.bufferData(gl.ARRAY_BUFFER, json.decoded.vertex_buffer, gl.STATIC_DRAW);
                gl.bindBuffer(gl.ARRAY_BUFFER, null);

                this.index_buffer_id = gl.createBuffer();
                gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, this.index_buffer_id);
                gl.bufferData(gl.ELEMENT_ARRAY_BUFFER, json.decoded.index_buffer, gl.STATIC_DRAW);
                gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, null);
            },

            bind_vertex_attrib: function(gl, attr, mesh, json, elements, element_name) {
                if (elements.hasOwnProperty(element_name)) {
                    var element = elements[element_name];

                    var gl_type = null;
                    switch(element.type) {
                        case "float":
                            gl_type = gl.FLOAT;
                            break;
                        case "ubyte":
                            gl_type = gl.UNSIGNED_BYTE;
                            break;
                    }

                    gl.vertexAttribPointer(attr, element.size, gl_type, false, json.stride,
                                           mesh.vertex_index * json.stride + element.offset);
                }
            },

            render: function(gl, prg, view_matrix) {
                var json = this.json;

                if ($.isEmptyObject(json)) {
                    return false;
                }
                if (gl === null) {
                    throw "render called with empty gl";
                }

                if (this.vertex_buffer_id === null) {
                    this.init_buffers(gl);
                }

                if (this.new_material_version !== null) {
                    this.init_materials();
                }

                // Bind model mat
                gl.uniformMatrix4fv(prg.uMMatrix, false, this.model_matrix);

                var invMVMatrix = mat4.create();
                mat4.mul(invMVMatrix, view_matrix, this.model_matrix);
                mat4.invert(invMVMatrix, invMVMatrix);
                gl.uniformMatrix4fv(prg.uInvMVMatrix, false, invMVMatrix);

                var elements = json.elements;

                // Draw each mesh
                for (var i = 0; i < json.meshes.length; i++) {
                    var mesh = json.meshes[i];

                    gl.bindBuffer(gl.ARRAY_BUFFER, this.vertex_buffer_id);

                    this.bind_vertex_attrib(gl, prg.attrs.aVertexPosition, mesh, json, elements, "position");
                    this.bind_vertex_attrib(gl, prg.attrs.aTextureCoord, mesh, json, elements, "tex_coord");
                    this.bind_vertex_attrib(gl, prg.attrs.aVertexNormal, mesh, json, elements, "normal");
                    this.bind_vertex_attrib(gl, prg.attrs.aVertexBinormal, mesh, json, elements, "binormal");
                    this.bind_vertex_attrib(gl, prg.attrs.aVertexColor, mesh, json, elements, "color");

                    gl.bindBuffer(gl.ARRAY_BUFFER, null);

                    var material = this.materials[i];

                    if (material === null) {
                    	gl.uniform1i(prg.uDiffuse, false);
                    	gl.uniform1i(prg.uSpecular, false);
                    	gl.uniform1i(prg.uNormal, false);
                        gl.uniform1i(prg.uTable, false);
                    } else {
	                    var diffuse = material.get_texture("diffuse");
	                    if (diffuse && diffuse.bind(gl, prg.uDiffuseTex, 0)) {
	                        gl.uniform1i(prg.uDiffuse, true);
	                    } else {
	                        gl.uniform1i(prg.uDiffuse, false);
	                    }

	                    var specular = material.get_texture("specular");
	                    if (specular && specular.bind(gl, prg.uSpecularTex, 1)) {
	                        gl.uniform1i(prg.uSpecular, true);
	                    } else {
	                        gl.uniform1i(prg.uSpecular, false);
	                    }

	                    var normal = material.get_texture("normal");
	                    if (normal && normal.bind(gl, prg.uNormalTex, 2)) {
	                        gl.uniform1i(prg.uNormal, true);
	                    } else {
	                        gl.uniform1i(prg.uNormal, false);
	                    }

                        var table = material.get_texture("table");
                        if (table && table.bind(gl, prg.uTableTex, 3)) {
                            gl.uniform1i(prg.uTable, true);
                        } else {
                            gl.uniform1i(prg.uTable, false);
                        }

                        var mask = material.get_texture("mask");
                        if (mask && mask.bind(gl, prg.uMaskTex, 4)) {
                            gl.uniform1i(prg.uMask, true);
                        } else {
                            gl.uniform1i(prg.uMask, false);
                        }
                	}

                    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, this.index_buffer_id);
                    gl.drawElements(gl.TRIANGLES, mesh.index_count, gl.UNSIGNED_SHORT, mesh.index_index * 2);
                    gl.bindBuffer(gl.ELEMENT_ARRAY_BUFFER, null);
                }
            }
        };

        return {
            d: self,

            render: self.render.bind(self),
            set_model_matrix: self.set_model_matrix.bind(self),
            set_material_version: self.set_material_version.bind(self)
        }
    };

    function create_shader(gl, type, src) {
        var data = $.ajax({
            url: src,
            async: false
        }).responseText;

        var shader = gl.createShader(type);

        gl.shaderSource(shader, data);
        gl.compileShader(shader);

        if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
            //console.log("Error at compile for: " + src);
            //console.log(gl.getShaderInfoLog(shader));
            return null;
        }

        return shader;
    }

    function create_program(gl, vs, fs, attrs, unifs) {
        var prg = gl.createProgram();
        gl.attachShader(prg, create_shader(gl, gl.VERTEX_SHADER, "shaders/" + vs + ".glsl"));
        gl.attachShader(prg, create_shader(gl, gl.FRAGMENT_SHADER, "shaders/" + fs + ".glsl"));
        gl.linkProgram(prg);

        if (!gl.getProgramParameter(prg, gl.LINK_STATUS)) {
            //console.log("Error at link for: " + vs + " - " + fs);
            //console.log(gl.getProgramInfoLog(prg));
            return null;
        }

        prg.attrs = {};

        for (var i = 0; i < attrs.length; i++) {
            prg.attrs[attrs[i]] = gl.getAttribLocation(prg, attrs[i]);
        }

        for (var i = 0; i < unifs.length; i++) {
            prg[unifs[i]] = gl.getUniformLocation(prg, unifs[i]);
        }

        return prg;
    }

    function get_extension(context, name) {
        var vendorPrefixes = ["", "WEBKIT_", "MOZ_", "IE_", "O_"];
        for (var i = 0; i < vendorPrefixes.length; i++) {
            var extension = context.getExtension(vendorPrefixes[i] + name);
            if (extension) {
                return extension;
            }
        }
        //console.log("Could not find extension: " + name);
        return null;
    }

    function create_framebuffer(gl, size) {
        var fb = {};
        fb.buffer = gl.createFramebuffer();

        gl.bindFramebuffer(gl.FRAMEBUFFER, fb.buffer);

        fb.texture = gl.createTexture();
        gl.bindTexture(gl.TEXTURE_2D, fb.texture);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_S, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_WRAP_T, gl.CLAMP_TO_EDGE);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MIN_FILTER, gl.LINEAR);
        gl.texParameteri(gl.TEXTURE_2D, gl.TEXTURE_MAG_FILTER, gl.LINEAR);
        gl.texImage2D(gl.TEXTURE_2D, 0, gl.RGBA, size, size, 0, gl.RGBA, gl.UNSIGNED_BYTE, null);

        var renderbuffer = gl.createRenderbuffer();
        gl.bindRenderbuffer(gl.RENDERBUFFER, renderbuffer);
        gl.renderbufferStorage(gl.RENDERBUFFER, gl.DEPTH_COMPONENT16, size, size);

        gl.framebufferTexture2D(gl.FRAMEBUFFER, gl.COLOR_ATTACHMENT0, gl.TEXTURE_2D, fb.texture, 0);
        gl.framebufferRenderbuffer(gl.FRAMEBUFFER, gl.DEPTH_ATTACHMENT, gl.RENDERBUFFER, renderbuffer);

        gl.bindTexture(gl.TEXTURE_2D, null);
        gl.bindFramebuffer(gl.FRAMEBUFFER, null);
        gl.bindRenderbuffer(gl.RENDERBUFFER, null);

        return fb;
    }

    function create_model_viewer(canvas, options) {
        // Model Viewer Object
        var self = {
            // Variables
            canvas: canvas,

            gl: null,
            options: $.extend({
                alpha_test: true,
                colors: true,
                antialias: true
            }, options),

            // Models to render
            models: [],
            // First pass program
            fp_prg: null,
            lp_prg: null,
            pp_prgs: {},

            pp_steps: [],

            // Projection matrix
            projection_matrix: mat4.perspective(mat4.create(), 45.0, canvas.width/canvas.height, 0.1, 100.0),
            // View matrix
            view_matrix: mat4.translate(mat4.create(), mat4.create(), [0.0, -3.0, -10.0]),
            // Control the drawing or not
            stopped: true,

            // framebuffers
            framebuffers: [],

            fb_size: 2048,

            pp_scan_buffer: null,

            // Private methods
            draw: function() {
                if (!this.stopped)
                {
                    requestAnimFrame(this.draw.bind(this));

                    var gl = this.gl;

                    gl.useProgram(this.fp_prg);

                    gl.bindFramebuffer(gl.FRAMEBUFFER, this.framebuffers[1].buffer);
                    gl.viewport(0, 0, this.fb_size, this.fb_size);
                    //gl.viewport(0, 0, this.canvas.width, this.canvas.height);

                    gl.clear(gl.COLOR_BUFFER_BIT | gl.DEPTH_BUFFER_BIT);

                    for (var attr in this.fp_prg.attrs) {
                        gl.enableVertexAttribArray(this.fp_prg.attrs[attr]);
                    }

                    // Binding uniforms
                    gl.uniformMatrix4fv(this.fp_prg.uPMatrix, false, this.projection_matrix);
                    gl.uniformMatrix4fv(this.fp_prg.uVMatrix, false, this.view_matrix);

                    // Call render on each model with the options in param
                    for (var i = 0; i < this.models.length; i++)
                    {
                        this.models[i].render(this.gl, this.fp_prg, this.view_matrix);
                    }

                    for (var attr in this.fp_prg.attrs) {
                        gl.disableVertexAttribArray(this.fp_prg.attrs[attr]);
                    }

                    var prg_nb;
                    for (prg_nb = 0; prg_nb < this.pp_steps.length; prg_nb++)
                    {
                        gl.bindFramebuffer(gl.FRAMEBUFFER, this.framebuffers[prg_nb % 2].buffer);
                        var prg = this.pp_prgs[this.pp_steps[prg_nb]];

                        gl.useProgram(prg);

                        for (var attr in prg.attrs) {
                            gl.enableVertexAttribArray(prg.attrs[attr]);
                        }

                        gl.uniform1f(prg.uSize, this.fb_size);

                        gl.activeTexture(gl.TEXTURE0);
                        gl.bindTexture(gl.TEXTURE_2D, this.framebuffers[(prg_nb + 1) % 2].texture);
                        gl.uniform1i(prg.uInTex, 0);

                        gl.bindBuffer(gl.ARRAY_BUFFER, this.pp_scan_buffer);
                        gl.vertexAttribPointer(prg.attrs.aPosition, 2, gl.FLOAT, false, 0, 0);

                        gl.drawArrays(gl.TRIANGLES, 0, 6);
                        gl.bindTexture(gl.TEXTURE_2D, this.framebuffers[prg_nb%2].texture);

                        for (var attr in prg.attrs) {
                            gl.disableVertexAttribArray(prg.attrs[attr]);
                        }
                    }

                    gl.bindFramebuffer(gl.FRAMEBUFFER, null);
                    gl.viewport(0, 0, this.canvas.width, this.canvas.height);

                    gl.useProgram(this.lp_prg);
                    for (var attr in this.lp_prg.attrs) {
                        gl.enableVertexAttribArray(this.lp_prg.attrs[attr]);
                    }

                    gl.uniform1f(this.lp_prg.uSize, this.fb_size);

                    gl.activeTexture(gl.TEXTURE0);
                    gl.bindTexture(gl.TEXTURE_2D, this.framebuffers[(prg_nb + 1) % 2].texture);
                    gl.uniform1i(this.lp_prg.uInTex, 0);

                    gl.bindBuffer(gl.ARRAY_BUFFER, this.pp_scan_buffer);
                    gl.vertexAttribPointer(this.lp_prg.attrs.aPosition, 2, gl.FLOAT, false, 0, 0);

                    gl.drawArrays(gl.TRIANGLES, 0, 6);

                    for (var attr in this.lp_prg.attrs) {
                        gl.disableVertexAttribArray(this.lp_prg.attrs[attr]);
                    }
                }
            },

            // Public methods
            // Options
            update_options: function(opt) {
                $.extend(this.options, opt);
                return this;
            },

            // Start & Stop
            start: function() {
                if (this.stopped)
                {
                    this.stopped = false;
                    this.draw();
                }
                return this;
            },
            stop: function() {
                this.stopped = true;
                return this;
            },

            // Matrices API
            set_projection_matrix: function(proj) {
                this.projection_matrix = mat4.clone(proj);
                return this;
            },
            set_view_matrix: function(view) {
                this.view_matrix = mat4.clone(view);
                return this;
            },

            // Model API
            attach: function(name) {
                var model = create_model(name);
                this.models.push(model);
                return model;
            },
            detach: function(model) {
                var model_id = this.models.indexOf(model);
                if (model_id != -1)
                {
                    this.models.splice(model_id, 1);
                }
            }
        }

        // Create context
        var gl_options = {
           antialias: false
        };

        self.gl =   canvas.getContext("experimental-webgl", gl_options)
                    || canvas.getContext("webgl", gl_options);

        if (!self.gl)
        {
            //console.log("Failed to initialize webgl context.");
            return null;
        }

        var gl = self.gl;

        // Clear screen
        gl.clearColor(0.0, 0.0, 0.0, 0.0);
        gl.enable(gl.DEPTH_TEST);
        gl.enable(gl.CULL_FACE);
        gl.cullFace(gl.BACK);

        // Check extensions
        gl.extensions = {
            compressedTextureS3tc: get_extension(gl, "WEBGL_compressed_texture_s3tc"),
            textureFloat: get_extension(gl, "OES_texture_float")
        };

        if (!gl.extensions.compressedTextureS3tc) {
            alert("gl extension WEBGL_compressed_texture_s3tc has not been found!");
        }

        // First pass processing
        self.fp_prg = create_program(gl, "fp_vs", "fp_fs",
            ["aVertexPosition", "aTextureCoord", "aVertexNormal", "aVertexBinormal", "aVertexColor"],
            ["uPMatrix", "uVMatrix", "uMMatrix", "uInvMVMatrix",
             "uDiffuse", "uSpecular", "uNormal", "uTable", "uMask",
             "uDiffuseTex", "uSpecularTex", "uNormalTex", "uTableTex", "uMaskTex"]);

        // Post processing
        self.framebuffers.push(create_framebuffer(gl, self.fb_size));
        self.framebuffers.push(create_framebuffer(gl, self.fb_size));

        // PostProcessing scan buffer
        self.pp_scan_buffer = gl.createBuffer();
        gl.bindBuffer(gl.ARRAY_BUFFER, self.pp_scan_buffer);
        gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, -1, 1, 1, -1, 1, 1]), gl.STATIC_DRAW);

        self.pp_prgs.fxaa = create_program(gl, "pp_vs", "fxaa_fs",
            ["aPosition"],
            ["uSize", "uInTex"]);
        self.pp_prgs.glow = create_program(gl, "pp_vs", "glow_fs",
            ["aPosition"],
            ["uSize", "uInTex"]);

        //self.pp_steps.push("fxaa");
        //self.pp_steps.push("glow");

        // last pass
        self.lp_prg = create_program(gl, "pp_vs", "lp_fs",
            ["aPosition"],
            ["uSize", "uInTex"]);

        // Return ModelViewer object public API
        return {
            d: self,

            update_options: self.update_options.bind(self),

            start: self.start.bind(self),
            stop: self.stop.bind(self),

            set_projection_matrix: self.set_projection_matrix.bind(self),
            set_view_matrix: self.set_view_matrix.bind(self),

            attach: self.attach.bind(self),
            detach: self.detach.bind(self)
        };
    };

    return {
        create_model_viewer: create_model_viewer,
        set_progress_callback: set_progress_callback
    }
}());
