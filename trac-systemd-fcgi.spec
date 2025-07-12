Name:           trac-systemd-fcgi
Version:        1.6
Release:        1%{?dist}
Summary:        Trac FastCGI wrapper and systemd unit

License:        BSD-3-Clause
URL:            https://github.com/seinolab/trac-systemd-fcgi/
Source0:        %{name}-%{version}.tar.gz

BuildArch:      noarch
%{?sysusers_requires_compat}

%description
Provides a FastCGI script and systemd socket/service for launching Trac via socket activation.

Requires:       trac
Requires:       python3-setproctitle

%prep
%setup -q

%build
# Nothing to build

%install
# Install script
install -D -m 0755 trac.fcgi %{buildroot}/usr/libexec/trac/trac.fcgi

# Install systemd units
install -D -m 0644 trac@.service %{buildroot}/usr/lib/systemd/system/trac@.service
install -D -m 0644 trac@.socket  %{buildroot}/usr/lib/systemd/system/trac@.socket

# Install sysusers file
install -D -m 0644 trac-systemd-fcgi.sysusers %{buildroot}/usr/lib/sysusers.d/trac-systemd-fcgi.conf

%files
%license LICENSE
%doc README.md
/usr/libexec/trac/trac.fcgi
/usr/lib/systemd/system/trac@.service
/usr/lib/systemd/system/trac@.socket
/usr/lib/sysusers.d/trac-systemd-fcgi.conf

%post
# create user/group if not already present
systemd-sysusers /usr/lib/sysusers.d/trac-systemd-fcgi.conf || :

%changelog
* Sat Jul 12 2025 Takahiro Seino, Ph.D. <5173733+seinolab@users.noreply.github.com> - 1.6-1
- Initial release
