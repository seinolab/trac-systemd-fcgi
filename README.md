# trac-systemd-fcgi

`trac-systemd-fcgi` provides a FastCGI wrapper script and systemd unit files to run [Trac](https://trac.edgewall.org/) as a socket-activated service under systemd.

This allows you to launch multiple Trac environments independently using `trac@<name>.socket` and `trac@<name>.service`, without requiring a standalone web server or persistent FastCGI daemon.

## Features

- systemd-based socket activation for Trac
- One Trac instance per project using `systemd` templates
- FastCGI-compatible: works with web servers like Nginx or Apache (with mod_proxy)
- Clean process isolation per Trac environment
- Process title customization

## Files Installed

- `/usr/libexec/trac/trac.fcgi` — FastCGI wrapper script
- `/usr/lib/systemd/system/trac@.socket` — systemd socket unit
- `/usr/lib/systemd/system/trac@.service` — systemd service unit
- `/usr/lib/sysusers.d/trac-systemd-fcgi.conf` — user/group definition (`trac`)

## Requirements

- Trac (Python-based project management tool)
- Python 3
- systemd
- Web server with FastCGI or proxy support (e.g., Nginx)
- `python3-setproctitle`

### Versioning

The version number of this software follows the version of [Trac](https://trac.edgewall.org/) it is compatible with.  
For example, `1.6` indicates compatibility with Trac 1.6.

This project itself is minimal and unlikely to change unless required by changes in Trac or systemd.

## Usage

### 1. Install from RPM

```bash
sudo dnf -y rpmbuild
curl -L "https://github.com/seinolab/trac-systemd-fcgi/archive/refs/tags/1.6.tar.gz" -o trac-systemd-fcgi-1.6.tar.gz
rpmbuild -tb --clean trac-systemd-fcgi-1.6.tar.gz
ls ~/rpmbuild/RPMS/noarch/trac-systemd-fcgi*.rpm | xargs dnf install -y
```

This will install the script and unit files, and register the `trac` system user.

### 2. Create a Trac environment

```bash
sudo mkdir -p /var/lib/trac/myproject
sudo trac-admin /var/lib/trac/myproject initenv
sudo chown -R trac:trac /var/lib/trac/myproject
```

### 3. Enable and start the socket

```bash
sudo systemctl enable --now trac@myproject.socket
```

### 4. (Optional) Customize Paths 

Invoke `sudo systemtl edit trac@.service` to customize paths and its process title.

```
[Service]
WorkingDirectory=/path/to/%i/
Environment=PYTHON_EGG_CACHE=/path/to/%i/.egg-cache
Environment=TRAC_ENV=/path/to/%i/
Environment=TRAC_PROCESS_TITLE="trac.fcgi (https://trac.example.com/%i)"
```

## License

BSD-3-Clause
